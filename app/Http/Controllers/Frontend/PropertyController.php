<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Jobs\Geocode;
use App\UserGroup;
use App\DataStore\DataAccess;
use App\PropertyMgr\Model\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Facades\Datatables;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $this->authorize('citynexus', ['properties', 'view']);

        return view('property.index');
    }

    public function allData()
    {
        $properties = Property::where('is_building', true);

        return Datatables::of($properties)
            ->addColumn('actions', function($property) {
                return '<a href="' . route('properties.show', [$property->id]) . '" class="btn btn-raised btn-primary btn-sm">Profile</a>';
            })
            ->rawColumns(['actions'])
            ->addColumn('units', function ($property) {
                return $property->units->count();
            })
            ->editColumn('address', '{{title_case($address)}}')
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
    public function show($id, DataAccess $dataAccess)
    {
        $property = Property::find($id);
        $datasets = $dataAccess->getDataByPropertyID($id);

        return view('property.show', compact('property', 'datasets'));
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

    /**
     * @return string
     */
    public function geocode($id)
    {
        $this->dispatch(new Geocode($id));
        return redirect()->back();
    }
}
