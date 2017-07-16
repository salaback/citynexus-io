<?php

namespace App\Http\Controllers;

use App\Client;
use App\Terms;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{

    public function getLogin()
    {
        return view('auth.login');
    }
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

            if(isset($user->memberships[$client->domain] ))
            {
                $app_key = config('app.key');

                if(isset($client->settings['app_key']))
                {
                    config(['app.key' => $client->settings['app_key']]);
                }

                if (isset($user->memberships[$client->domain]['password']) && Hash::check($request->get('password'), $user->memberships[$client->domain]['password'])) {
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

    /**
     * @return string
     */
    public function getLogout()
    {
        Auth::logout();
        return redirect('/');
    }


    public function activate(Request $request)
    {
        $user = User::where('email', $request->get('key'))->first();
        $terms = Terms::orderBy('adopted_at')->first();

        return view('auth.activate', compact('user', 'terms'))->with('key', $request->get('key'));
    }

    public function postActivate(Request $request)
    {
        $this->validate($request, [
            'password' =>  'required|between:8,250|confirmed',
            'agree' => 'required'
        ]);

        $now = Carbon::now();

        $user = User::where('activation', $request->get('key'))->first();

        $user->password = Hash::make($request->get('password'));
        $user->accepted_terms = $now;
        $acceptedTerms = $user->terms;
        $acceptedTerms[$now->toDayDateTimeString()] = Terms::find($request->get('termsId'))->terms;
        $user->terms = $acceptedTerms;

        $user->save();

        Auth::login($user, true);

        return redirect('/');
    }

}
