<?php

namespace App\Http\Controllers;

use App\Client;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'email'     => 'required',
            'password'  => 'required'
        ]);

        $email = strtolower($request->get('email'));

        if (Auth::attempt(['email' => $email, 'password' => $request->get('password')])) {
            // Authentication passed...
            return redirect()->intended('/');
        }
        elseif(User::where('email', $email)->whereNotNull('memberships')->count())
        {
            $user = User::where('email', $email)->first();

            $client = Client::where('domain', $_SERVER['HTTP_HOST'])->first();

            if(isset($user->memberships[$client->schema]))
            {
                $app_key = config('app.key');
                config(['app.key' => $client->settings['app_key']]);
                if (Hash::check($request->get('password'), $user->memberships[$client->schema]['password'])) {
                    // Reapply key
                    config(['app.key' => $app_key]);
                    Auth::login($user);
                    return redirect()->intended('/');
                }
            }
        }


        Session::flash('flash_info', "Sorry! That user or password doesn't match our records. Please try again.");
        return redirect()->back();
    }
}
