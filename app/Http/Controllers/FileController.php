<?php

namespace App\Http\Controllers;

use CityNexus\PropertyMgr\File;
use CityNexus\PropertyMgr\FileVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FileController extends Controller
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
        $file = File::create($request->all());
        $version = FileVersion::create([
            'added_by'  => Auth::getUser()->id,
            'size'      => intval($request->get('size') / 1000),
            'type'      => $request->get('type'),
            'source'    => $request->get('source'),
            'file_id'   => $file->id
        ]);
        $file->version_id = $version->id;
        $file->save();

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
        $file = File::find($id);

        if(substr($file->type, 0, 6) == 'image/')
        {
            return $file->getImage();
        }
        else
        {
            return redirect($file->current->source);
        }

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
        File::find($id)->delete();

        return response();
    }
}
