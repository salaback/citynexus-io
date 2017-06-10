<?php

namespace App\Http\Controllers\Frontend;

use App\AnalysisMgr\Model\Score;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

        return 'Frontend\ScoreController@show';
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

    public function createElement(Request $request)
    {
        return view('analytics.score.snipits._element')
            ->with('element', $request->get('element'));
    }
}
