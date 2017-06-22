<?php

namespace App\Http\Controllers\Frontend;

use App\DocumentMgr\DocumentBuilder;
use App\DocumentMgr\Model\Document;
use App\Http\Controllers\Controller;
use App\PropertyMgr\Model\Entity;
use App\PropertyMgr\Model\Property;
use App\User;
use Illuminate\Http\Request;

class DocumentController extends Controller
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
        $models = [];
        if($request->exists('entity_id'))
            $models['entity'] = Entity::find($request->get('entity_id'));

        if($request->exists('sender_id'))
            $models['sender'] = User::find($request->get('sender_id'));

        if($request->exists('property_id'))
            $models['property'] = Property::find($request->get('property_id'));

        $documentBuilder = new DocumentBuilder();

        $document = $documentBuilder->buildDocument($request->get('template_id'), $models);

        return redirect(route('documents.edit', [$document->id]));
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
        $document = Document::find($id);

        return view('documents.edit', compact('document'));
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
}
