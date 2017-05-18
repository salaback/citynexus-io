<?php

namespace App\Http\Controllers\Admin;

use App\Group;
use App\Http\Controllers\Controller;
use App\User;
use App\UserGroup;
use Illuminate\Http\Request;

class OrganizationSettingsController extends Controller
{
    public function index()
    {
        $users = User::fromClient();
        $groups = UserGroup::all();
        return view('admin.organization.index', compact('users', 'groups'));
    }

    public function editUser($id)
    {
        $user = User::find($id);
        $groups = UserGroup::all();
        $membership = $user->memeberships[config('schema')];
        return view('admin.organization.user.edit', compact('user', 'membership', 'groups'));
    }

    public function storeUser(User $user, Request $request)
    {
        $user->update($request->get('user'));

        return redirect(action('Admin\OrganizationSettingsController@index'));
    }
}
