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
        $this->authorize('citynexus', ['org-admin', 'view']);

        $users = User::fromClient();

        $groups = UserGroup::all();
        return view('admin.organization.index', compact('users', 'groups'));
    }


    public function storeUser(User $user, Request $request)
    {

    }
}
