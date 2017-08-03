<?php

namespace App\Http\Controllers\Frontend;

use App\Client;
use App\DocumentMgr\DocumentBuilder;
use App\DocumentMgr\Model\DocumentTemplate;
use App\Http\Controllers\Controller;
use App\PropertyMgr\Model\Entity;
use App\PropertyMgr\Model\Property;
use App\User;
use Illuminate\Http\Request;

class DocumentTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $templates = DocumentTemplate::all();

        return view('documents.templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('documents.templates.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DocumentTemplate::create($request->all());

        return redirect(route('templates.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $template = DocumentTemplate::find($id);

        return $template;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $template = DocumentTemplate::orderBy('name')->paginate(15);

        return view('documents.templates.edit', compact('template'));
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
        $template = DocumentTemplate::find($id);

        $template->update($request->all());

        session()->flash('flash_success', "Changes to template have been saved.");
        return redirect(route('templates.index'));
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

    public function getForm($id, Request $request)
    {
        $documentBuilder = new DocumentBuilder();
        $template = DocumentTemplate::find($id);
        if($request->get('property_id') != null)
            $property = Property::find($request->get('property_id'));
        else
            $property = null;

        if($request->get('entity_id') != null)
            $entity = Entity::find($request->get('entity_id'));
        else
            $entity = null;


        if($property != null && $property->entities->count() > 0)
            foreach($property->entities as $item)
                $entity_data[] = ['id' => $item->id, 'text' => $item->name];
        else
            $entity_data = [];

        if($entity != null && $entity->properties->count() > 0)
        foreach($entity->properties->where('is_building', true)->get(['id', 'address']) as $item)
            $building_data[] = ['id' => $item->id, 'text' => $item->address];
        else
            $building_data = [];

        $user = new User();
        $users = $user->fromClient();
        $sender_data = array();
        foreach($users as $item)
            $sender_data[] = ['id' => $item->id, 'text' => $item->fullname];

        $data = $documentBuilder->createDataArray($template->body);

        $data = $documentBuilder->loadDataArray($data, ['template' => $template, 'property' => $property, 'entity' => $entity]);
        return view('snipits._document_template_form', compact('template', 'property', 'entity', 'data', 'entity_data', 'building_data', 'sender_data'));
    }
}
