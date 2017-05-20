<?php

namespace App\Http\Controllers\Auth;

use App\UserGroup;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class UserGroupController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('citynexus', ['org-admin', 'groups']);

        return view('auth.user_group.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('citynexus', ['org-admin', 'groups']);

        $this->validate($request,
            [
                'name'          => 'required|max:255',
                'permissions'   => 'required'
            ]);

        if($userGroup = UserGroup::create($request->all()))
        {
            return redirect('/organization');
        }
        else
            Session::flash('flash_error', 'Something went wrong.');

        session()->flash('flash_info', $userGroup->name . ' has been created.');

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
        $this->authorize('citynexus', ['org-admin', 'groups']);

        $userGroup = UserGroup::find($id);

        $permissions = $userGroup->permissions;

        return view('auth.user_group.create', compact('permissions', 'userGroup'));
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
        $this->authorize('citynexus', ['org-admin', 'groups']);

        $userGroup = UserGroup::find($id);

        $userGroup->update($request->all());

        session()->flash('flash_info', $userGroup->name . ' has been updated.');


        return redirect('/organization');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $this->authorize('citynexus', ['org-admin', 'groups']);

        $userGroup = UserGroup::find($id);

        DB::table('user_user_group')->where("user_group_id", $userGroup->id)->delete();

        $name = $userGroup->name;

        $userGroup->delete();

        session()->flash('flash_info', $name . ' has been deleted.');

        return redirect('/organization');
    }

    /**
     *
     * Add user to user group
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Symfony\Component\HttpFoundation\Response
     */
    public function addUserToGroup(Request $request)
    {
        $this->authorize('citynexus', ['org-admin', 'assign-groups']);

        // Check if already attached
        $count = DB::table('user_user_group')
            ->where('user_id', $request->get('user_id'))
            ->where('user_group_id', $request->get('group_id'))
            ->count();


        // if not attached, then attach
        if($count == 0)
        {
            $update = DB::table('user_user_group')->insert([
                'user_id' => $request->get('user_id'),
                'user_group_id' => $request->get('group_id')
            ]);

            if($update)
            {
                $count = 1;
            }
        }

        // if successful let us know
        if($count >= 1)
        {
            $group = UserGroup::find($request->get('group_id'));

            return view('auth.user_group._group_snip', compact('group'));
        }
        else
        {
            return response('Error attaching user to group.', 505);
        }

    }

    /**
     *
     * Remove user from userGroup
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function removeUserFromGroup(Request $request)
    {
        $this->authorize('citynexus', ['org-admin', 'assign-groups']);

        // detach and instances
        DB::table('user_user_group')
            ->where('user_id', $request->get('user_id'))
            ->where('user_group_id', $request->get('group_id'))
            ->delete();

        return response('User successfully detached.', 200);

    }

}
