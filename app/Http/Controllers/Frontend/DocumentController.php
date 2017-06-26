<?php

namespace App\Http\Controllers\Frontend;

use App\DocumentMgr\DocumentBuilder;
use App\DocumentMgr\Model\Document;
use App\DocumentMgr\Model\DocumentTemplate;
use App\DocumentMgr\Model\PrintQueue;
use App\Http\Controllers\Controller;
use App\PropertyMgr\Model\Entity;
use App\PropertyMgr\Model\Property;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $document = $request->all();
        $models = [];
        if($request->exists('entity_id'))
        {
            $models['entity'] = Entity::find($request->get('entity_id'));
            $document['related']['App\\PropertyMgr\\Model\\Entity'] =  $request->get('entity_id');
        }

        if($request->exists('sender_id')) {
            $models['sender'] = User::find($request->get('sender_id'));
        }

        if($request->exists('property_id')) {
            $models['property'] = Property::find($request->get('property_id'));
            $document['related']['App\\PropertyMgr\\Model\\Property'] =  $request->get('property_id');
        }

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
        try {
            $document = Document::find($id);

            // queue document to print
            PrintQueue::create([
                'cn_document_id' => $document->id,
                'created_by' => Auth::id()
            ]);


            // Update document status
            $document->status = 'queued';

            // Update document history
            $history = $document->history;
            $history[Carbon::now()->toDateTimeString()] = [
                'action' => 'Create document and queue for printing',
                'body' => $request->get('body')
            ];
            $document->body = $request->get('body');

            // save document model
            $document->save();


            // alert user document has been queued
            session()->flash('flash_success', 'Document queued for printing');

            return redirect('/');
        }
        catch (\Exception $e)
        {
            session()->flash('flash_danger', "Uh oh. Something went wrong.");
            return redirect()->back();
        }

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
