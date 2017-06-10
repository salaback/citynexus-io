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
    public function show($id, Request $request)
    {
        if($request->exists('to') || $request->exists('from'))
        {
            $count['properties']['tagged'] = DB::table('cn_tagables')->where('created_at', '<', $request->get('to'))->where('created_at', '>=', $request->get('from'))->where('tag_id', $id)->where('tagables_type', 'App\PropertyMgr\Model\Property')->whereNull('deleted_at')->count();
            $count['properties']['deleted'] = DB::table('cn_tagables')->where('created_at', '<', $request->get('to'))->where('created_at', '>=', $request->get('from'))->where('tag_id', $id)->where('tagables_type', 'App\PropertyMgr\Model\Property')->whereNotNull('deleted_at')->count();

            $count['entities']['tagged'] = DB::table('cn_tagables')->where('created_at', '<', $request->get('to'))->where('created_at', '>=', $request->get('from'))->where('tag_id', $id)->where('tagables_type', 'App\PropertyMgr\Model\Entity')->whereNull('deleted_at')->count();
            $count['entities']['deleted'] = DB::table('cn_tagables')->where('created_at', '<', $request->get('to'))->where('created_at', '>=', $request->get('from'))->where('tag_id', $id)->where('tagables_type', 'App\PropertyMgr\Model\Entity')->whereNotNull('deleted_at')->count();

            $count['range']['from'] = $request->get('from');
            $count['range']['to'] = $request->get('to');

        }
        else{
            $count['properties']['tagged'] = DB::table('cn_tagables')->where('tag_id', $id)->where('tagables_type', 'App\PropertyMgr\Model\Property')->whereNull('deleted_at')->count();
            $count['properties']['deleted'] = DB::table('cn_tagables')->where('tag_id', $id)->where('tagables_type', 'App\PropertyMgr\Model\Property')->whereNotNull('deleted_at')->count();

            $count['entities']['tagged'] = DB::table('cn_tagables')->where('tag_id', $id)->where('tagables_type', 'App\PropertyMgr\Model\Entity')->whereNull('deleted_at')->count();
            $count['entities']['deleted'] = DB::table('cn_tagables')->where('tag_id', $id)->where('tagables_type', 'App\PropertyMgr\Model\Entity')->whereNotNull('deleted_at')->count();

            $dates = DB::table('cn_tagables')->where('tag_id', $id)->orderBy('created_at')->pluck('created_at')->toArray();

            if(count($dates) != 0) $count['range']['from'] = date('m/d/Y', date(strtotime(array_shift($dates))));
            if(count($dates) != 0) $count['range']['to'] = date('m/d/Y', date(strtotime(last($dates))));
        }


        return $count;
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

        $id = DB::table('cn_tagables')
            ->insertGetId([
                'tagables_type' => $request->get('tagable_type'),
                'tagables_id' => $request->get('tagable_id'),
                'tag_id' => $tag->id,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id()
            ]);

        $return = (object) [
            'id' => $tag->id,
            'tag' => $tag->tag,
            'pivot' => (object) [
                'id' => $id,
                'tagables_type' => $request->get('tagable_type'),
                'tagables_id' => $request->get('tagable_id'),
                'tag_id' => $tag->id,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id()
            ]
        ];

        return view('snipits._tag')->with('tag', $return);

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
