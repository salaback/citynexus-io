<?php

namespace App\Http\Controllers\Frontend;

use App\AnalysisMgr\Model\Score;
use App\DataStore\Model\DataSet;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $scores = Score::orderBy('name')->paginate(10);
        return view('analytics.score.index', compact('scores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('citynexus', ['analytics', 'score-create']);
        return view('analytics.score.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('citynexus', ['analytics', 'score-create']);

        $this->validate($request, [
            'name' => 'max:255|required',
            'type' => 'required',
            'elements' => 'required'
        ]);

        $score = $request->all();
        $elements = $request->get('elements');
        foreach($elements as $key => $item)
        {
            $elements[$key] = json_decode($item);
        }

        $score['elements'] = $elements;
        $score['owned_by'] = Auth::id();

        $score = Score::create($score);

        return redirect(route('score.show', [$score->id]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('citynexus', ['analytics', 'score-view']);

        $score = Score::find($id);

        $results = DB::table("cn_score_" . $id)
            ->join('cn_properties', 'cn_score_' . $id . '.property_id', '=', 'cn_properties.id')
            ->select('cn_properties.address', 'cn_score_' . $id . '.*')
            ->orderBy('score', 'DESC')
            ->paginate(20);

        $datasets = DataSet::all();

        $shades = $this->createShades($score->elements);

        $scores['min'] = DB::table('cn_score_' . $id)->min('score');
        $scores['max'] = DB::table('cn_score_' . $id)->max('score');

        return view('analytics.score.show', compact('score', 'results', 'datasets', 'scores', 'shades'));
    }

    private function createShades($elements)
    {
        $shades = [];
        $variation = 1 / count($elements);
        $current = 1;

        foreach($elements as $item)
        {
            switch ($item['type'])
            {
                case 'datapoint':
                    $shades['datapoints'][$item['dataset_id'] . '_' . $item['key']] = $current;
                    break;
            }

            $current -= $variation;
        }
        return $shades;
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
        Score::find($id)->delete();

        return 'deleted';
    }

    public function refresh($id)
    {
        $score = Score::find($id);

        $score->touch();

        return redirect(route('score.show', [$id]));
    }

    public function createElement(Request $request)
    {
        return view('analytics.score.snipits._element')
            ->with('element', $request->get('element'));
    }
}
