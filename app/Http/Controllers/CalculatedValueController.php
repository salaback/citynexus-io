<?php

namespace App\Http\Controllers;

use App\Jobs\CalculateValue;
use CityNexus\AnalysisMgr\CalculatedValue;
use CityNexus\AnalysisMgr\Calculator;
use CityNexus\DataStore\DataSet;
use CityNexus\DataStore\TableBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalculatedValueController extends Controller
{

    private $tableBuilder;
    private $helper;


    public function __construct()
    {

        $this->tableBuilder = new TableBuilder();
        $this->helper = new Calculator();
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $values = CalculatedValue::all();

        return view('calculated-value.index', compact('values'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tables = DataSet::all();

        return view('calculated-value.create', compact('tables'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'settings' => 'required',
        ]);

        $value = $request->all();
        $value['user_id'] = Auth::id();
        $value['key'] = $this->tableBuilder->cleanName($request->get('name'));

        $value = CalculatedValue::create($value);

        $this->helper->addNewValue($value->key, $value->type);

        $this->dispatch(new CalculateValue(config('client.id'), $value->id));

        return redirect(route('calculated-value.index'));
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

    public function refresh(Request $request)
    {
        $this->helper->calculateValue($request->get('id'));

        return response('Success');
    }
}
