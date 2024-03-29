<?php

namespace App\Http\Controllers\Frontend;

use App\DocumentMgr\Model\DocumentTemplate;
use App\Http\Controllers\Controller;
use App\Jobs\Geocode;
use App\PropertyMgr\Merge;
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
    public function index(Request $request)
    {
        $this->authorize('citynexus', ['properties', 'view']);

        if($request->ajax())
        {
            if(isset($_GET['type']))
            {
                switch ($_GET['type'])
                {
                    case 'select':
                        $return = [];
                        $properties = Property::all();
                        foreach($properties as $i)
                        {
                            $return[] = ['id' => $i->id, 'text' => $i->oneLineAddress];
                        }
                        return $return;
                }
            }
            return Property::all();
        }
        else
        {
            return view('property.index');
        }
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

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, DataAccess $dataAccess)
    {
        $property = Property::where('id', $id)->with('taskLists', 'units', 'comments')->first();

        $show_templates = [];
        $templates = DocumentTemplate::all();
        foreach($templates as $template)
        {
            if($property->is_building && isset($template->visible_on['buildings']))
            {
                $show_templates[] = $template;
            }
            if($property->is_unit && isset($template->visible_on['units']))
            {
                $show_templates[] = $template;
            }
        }

        $templates = $show_templates;

        $datasets = $dataAccess->getDataByPropertyID($id);

        return view('property.show', compact('property', 'datasets', 'templates'));
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
        $this->authorize('citynexus', ['properties', 'edit']);

        Property::find($id)->update($request->all());

        session()->flash('flash_success', 'Property Updated');

        return redirect()->back();
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

    public function getUnits($id)
    {
        $return =[];

        $units = Property::find($id)->units;

        foreach($units as $i) $return[] = ['id' => $i->id, 'text' => $i->unit];

        return $return;
    }

    public function mergeSearch($id, $string)
    {
        $string = '%' . strtoupper($string) . '%';
        $properties = Property::where('address', 'LIKE', $string)->where('id', '!=', $id)->get();
        return view('property.snipits._mergeResults', compact('properties'));
    }

    public function mergeProperties(Request $request)
    {
        $primary = $request->get('primary');
        $secondary = $request->get('secondary');
        $merge = new Merge();

        $merge->properties($primary, $secondary);

        session()->flash('flash_success', str_plural('Property', count($secondary)) . ' successfully merged');

        return redirect(route('properties.show', [$primary]));
    }

}
