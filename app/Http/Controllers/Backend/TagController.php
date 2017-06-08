<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\PropertyMgr\Model\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

    public function attach(Request $request)
    {
        $tag = Tag::firstOrCreate(['tag' => $request->get('tag')]);

        DB::table('cn_tagables')
            ->insert([
                'tagables_type' => $request->get('tagable_type'),
                'tagables_id' => $request->get('tagable_id'),
                'tag_id' => $tag->id,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id()
            ]);

        return view('snipits._tag', compact('tag'));

    }

    public function detach(Request $request)
    {

        DB::table('cn_tagables')->where('id', $request->get('tagable_id'))
            ->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::id()
            ]);

        return 'success';
    }
}
