<?php

namespace App\Http\Controllers\Frontend;

use App\DocumentMgr\Model\PrintQueue;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class PrintQueueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $printJobs = PrintQueue::orderBy('created_at')->whereNull('printed_at')->get();

        return view('queue.index', compact('printJobs'));
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

    public function clearFromQueue(Request $request)
    {
        $jobs = PrintQueue::findMany($request->get('ids'));

        foreach($jobs as $i)
        {
            $i->printed_by = Auth::id();
            $i->printed_at = Carbon::now();
            $i->save();
        }

        return 'cleared';
    }

    public function printQueue($id = null, Request $request)
    {
        if($id != null)
        {

        }
        elseif($request->exists('jobs'))
        {
            $jobs = PrintQueue::with('document')->find($request->get('jobs'));

            $pdf = \PDF::loadHTML(view('documents.pdf', compact('jobs')));
            return $pdf->stream();
        }
    }
}
