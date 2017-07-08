<?php

namespace App\Http\Controllers\Frontend;

use App\DataStore\DataInformation;
use App\DataStore\Model\DataSet;
use App\DataStore\TableBuilder;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\Datatables\Facades\Datatables;

class DatasetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('citynexus', ['datasets', 'view']);

        return view('dataset.index');
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function anyData()
    {

        $this->authorize('citynexus', ['datasets', 'view']);

        return Datatables::of(DataSet::query())
            ->addColumn('settings', function($dataset) {
                return '<a href="' . route('dataset.show', [$dataset->id]) . '" class="btn btn-raised btn-primary btn-sm">Settings</a>';
            })
            ->rawColumns(['settings'])
            ->addColumn('updated', function ($dataset) {
                return $dataset->updated_at->diffForHumans();
            })
            ->addColumn('owner', function ($dataset) {
                if($dataset->owner_id != null)
                    return $dataset->owner->fullname;
                else
                    return '';
            })
            ->make(true);
    }

    public function rawData($id)
    {

        $this->authorize('citynexus', ['datasets', 'view']);
        $dataset = DataSet::find($id);

        return Datatables::of(DB::table($dataset->table)->query())
            ->addColumn('property', function($set) {
                return '<a href="' . route('property.show', [$set->property_id]) . '" class="btn btn-raised btn-primary btn-sm">Settings</a>';
            })
            ->rawColumns(['settings'])
            ->make(true);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $this->authorize('citynexus', ['datasets', 'create']);

        $users = User::fromClient(config('client'));

        return view('dataset.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('citynexus', ['datasets', 'create']);

        $this->validate($request, [
            'name' => 'required|max:255',
            'type' => 'required',
            'owner_id' => 'required'
        ]);

        $dataset = $request->all();

        $dataset['schema'] = [];

        $dataset = DataSet::create($dataset);

        Session::flash('flash_success', 'Data Set successfully created');

        return redirect(route('dataset.show', [$dataset->id]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $dataset = DataSet::find($id);

        if($request->ajax())
        {
            return $dataset;
        }
        else
        {
            return view('dataset.show.' . $dataset->type, compact('dataset'));
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
        $dataset = DataSet::find($id);

        $name = $dataset->name;

        $dataset->delete();

        session()->flash('flash_info', 'Dataset "' . $name . '" has been deleted.');

        return redirect(route('dataset.index'));
    }


    public function datapointInfo($id, $key)
    {
        $dataset = DataSet::find($id);

        $dataInfo = new DataInformation();

        return $dataInfo->datapointInfo($dataset, $key);
    }
}
