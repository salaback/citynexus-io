<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\PropertyMgr\Model\Entity;
use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;

class EntityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('entity.index');
    }

    public function allData()
    {
        $entities = Entity::all();

        return Datatables::of($entities)
            ->addColumn('name', function($entity) {
               return $entity->name;
            })

            ->rawColumns(['actions'])
            ->addColumn('buildings', function ($entity) {
                return $entity->buildings->count();
            })
            ->addColumn('units', function ($entity) {
                return $entity->units->count();
            })
            ->editColumn('name', '{{title_case($name)}}')
            ->addColumn('actions', function($entity) {
                return '<a href="' . route('entity.show', [$entity->id]) . '" class="btn btn-raised btn-primary btn-sm">Profile</a>';
            })
            ->make(true);
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
        $entity = Entity::find($id);

        return view('entity.show', compact('entity'));
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
}
