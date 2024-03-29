<?php

namespace App\Http\Controllers\Admin;

use App\Group;
use App\Http\Controllers\Controller;
use App\User;
use App\UserGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrganizationSettingsController extends Controller
{
    public function index()
    {
        $this->authorize('citynexus', ['org-admin', 'view']);

        $users = User::fromClient()->sortBy('last_name');

        $groups = UserGroup::all();

        return view('admin.organization.index', compact('users', 'groups'));
    }

}