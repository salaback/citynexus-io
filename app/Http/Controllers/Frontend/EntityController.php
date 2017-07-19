<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\PropertyMgr\Model\Entity;
use App\PropertyMgr\Model\Property;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;

class EntityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax())
        {
            if(isset($_GET['type']))
            {
                switch ($_GET['type'])
                {
                    case 'select':
                        $return = [];
                        $entities = Entity::all();
                        foreach($entities as $i)
                        {
                            $return[] = ['id' => $i->id, 'text' => $i->name];
                        }
                        return $return;
                }
            }
            return Entity::all();
        }
        else
        {
            return view('entity.index');
        }

    }

    public function allData()
    {
        $entities = Entity::with('properties')->select('cn_entities.*');

        return Datatables::of($entities)
            ->addColumn('name', function($entity) {
               return $entity->name;
            })

            ->rawColumns(['actions'])
            ->addColumn('properties', function ($entity) {
                return $entity->properties->count();
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
        Entity::find($id)->update($request->all());

        session()->flash('flash_success', 'Entity Updated');

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

    public function addRelationship(Request $request)
    {
        if($request->exists('property_id'))
        {
            DB::table('cn_entitables')->insert([
                'entitables_type' => 'App\\PropertyMgr\\Model\\Property',
                'entitables_id' => $request->get('property_id'),
                'entity_id' => $request->get('entity_id'),
                'role'=> $request->get('role'),
                'created_at' => Carbon::now(),
            ]);

            session()->flash('flash_success', 'New relationship added.');

            return redirect()->back();
        }

    }

    public function setPrimaryAddress($id, $address)
    {
        DB::table('cn_entity')->update(['mailing_address_id' => $address])->where('id', $id);

        session()->flash('success', "Primary Address Set");

        return redirect(route('entity.show', [$id]) . '&tab=addresses');
    }


    public function removeRelationship($id)
    {
        DB::table('cn_entitables')->where('id', $id)->update(['deleted_at' => Carbon::now()]);
        session()->flash('flash-info', 'Relationship removed.');

        return redirect()->back();
    }
}
