<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\AnalysisMgr\MapHelper;
use App\DataStore\Model\DataSet;
use Illuminate\Http\Request;

class ViewController extends Controller
{

    private $mapHelper;

    public function __construct(MapHelper $mapHelper)
    {
        $this->mapHelper = $mapHelper;
    }
    /**
     *
     * Display blank map
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function map(Request $request)
    {
        $load = $request->get('load');
        $datasets = DataSet::all();

        return view('views.map', compact('load', 'datasets'));
    }

    /**
     *
     * Data for maps
     *
     * @param Request $request
     * @return json
     */
    public function mapData(Request $request)
    {
        switch ($request->get('type'))
        {
            case 'datapoint':
                return $this->mapHelper->createDatapoint($request->get('dataset_id'), $request->get('key'));
                break;
        }
    }
}
