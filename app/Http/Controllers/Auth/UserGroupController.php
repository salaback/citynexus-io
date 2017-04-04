<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\UserGroup;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class UserGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        $this->validate($request,
            [
                'name'          => 'required|max:255',
                'permissions'   => 'required'
            ]);

        if($group = UserGroup::create($request->all()))
        {
            return redirect(action('Auth\UserGroupController@show', [$group->id]));
        }
        else
            Session::flash('flash_error', 'Something went wrong.');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $group = Group::find($id);

        return view('auth.user_group.create', compact('group'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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

    public function addUserToGroup(Request $request)
    {

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

    public function removeUserFromGroup(Request $request)
    {

        // detach and instances
        DB::table('user_user_group')
            ->where('user_id', $request->get('user_id'))
            ->where('user_group_id', $request->get('group_id'))
            ->delete();

        return response('User successfully detached.', 200);

    }
}
