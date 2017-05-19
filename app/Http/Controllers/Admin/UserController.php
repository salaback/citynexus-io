<?php

namespace App\Http\Controllers\Admin;

use App\Events\UserCreated;
use App\Http\Controllers\Controller;
use App\User;
use App\UserGroup;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::fromClient();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $userGroups = UserGroup::all();
        return view('admin.users.create', compact('userGroups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|max:255',
            'department' => 'max:255',
            'title' => 'max:255',
            'groups' => 'required'
        ]);

        // get or create new user
        $user = User::firstOrNew(['email' => $request->get('email')]);

        if(!$user->exists)
        {
            // save contact information if user is new
            $user->first_name = $request->get('first_name');
            $user->last_name = $request->get('last_name');
            $user->password = str_random(24);

            $user->save();

            // fire new user event
            event(new UserCreated($user));
        }

        // Add membership information
        $user->addMemberships([config('schema') => [
            'title' => $request->get('title'),
            'department' => $request->get('department')
        ]]);

        // Attach to groups
        foreach($request->get('groups') as $group)
        {
            UserGroup::find($group)->users()->attach($user);
        }

        return redirect('/organization');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $groups = UserGroup::all();
        $membership = $user->memeberships[config('schema')];
        return view('admin.users.edit', compact('user', 'membership', 'groups'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        $user->update($request->get('user'));

        return redirect(action('Admin\OrganizationSettingsController@index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
