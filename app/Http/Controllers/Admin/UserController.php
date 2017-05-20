<?php

namespace App\Http\Controllers\Admin;

use App\Client;
use App\Events\UserCreated;
use App\Http\Controllers\Controller;
use App\Notifications\AddedToNewOrganization;
use App\User;
use App\UserGroup;
use Illuminate\Contracts\Session\Session;
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
        $this->authorize('citynexus', ['org-admin', 'create-users']);

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
        $this->authorize('citynexus', ['org-admin', 'create-users']);

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
        else
        {
            $client = Client::where('schema', config('schema'))->first();
            $user->notify(new AddedToNewOrganization($client));
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('citynexus', ['org-admin', 'edit-users']);

        $user = User::find($id);
        $groups = UserGroup::all();
        $membership = $user->memberships[config('schema')];
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

        $this->authorize('citynexus', ['org-admin', 'edit-users']);

        $user = User::find($id);

        $user->first_name = $request->get('first_name');
        $user->last_name = $request->get('last_name');
        $memberships = $user->memberships;
        $memberships[config('schema')]['title'] = $request->get('title');
        $memberships[config('schema')]['department'] = $request->get('department');
        $user->memberships = $memberships;

        session()->flash('flash_success','User ' . $user->fullname . ' updated.');

        $user->save();

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
        $this->authorize('citynexus', ['org-admin', 'remove-users']);

        $user = User::find($id);

        $memberships = $user->memberships;
        unset($memberships[config('schema')]);
        $user->memberships = $memberships;

        $user->save();

        session()->flash('flash_info', $user->fullname . ' has been removed from the organization');

        return redirect('/organization');
    }
}
