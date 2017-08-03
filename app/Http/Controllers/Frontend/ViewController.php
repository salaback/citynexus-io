<?php

namespace App\Http\Controllers\Frontend;

use App\AnalysisMgr\Model\Score;
use App\Http\Controllers\Controller;
use App\AnalysisMgr\MapHelper;
use App\DataStore\Model\DataSet;
use App\PropertyMgr\Model\Tag;
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
        $scores = Score::all();
        $tags = Tag::all();

        return view('views.map', compact('load', 'datasets', 'scores', 'tags'));
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

            case 'tag':
                return $this->mapHelper->createTagPoints($request->get('id'));
                break;

            case 'score':
                return $this->mapHelper->createScorePoints($request->get('id'));
                break;
        }
    }
}
