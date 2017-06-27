<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\TaskMgr\Model\TaskList;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if($request->exists('model_type') && $request->exists('model_id'))
        {
            return TaskList::where('taskable_type', $request->get('model_type'))
                ->where('taskable_id', $request->get('model_id'))
                ->with('tasks')
                ->get();
        }
        else
            return TaskList::with('tasks')->get();

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $list = TaskList::create($request->all());
        return view('snipits.tasks._task_list')
            ->with('list', $list)
            ->with('model_type', $list->taskable_type)
            ->with('model_id', $list->takable_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {


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


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $list = TaskList::find($id);
        foreach($list->tasks as $i) {
            $i->deleted_by = Auth::id();
            $i->deleted_at = Carbon::now();
            $i->save();
        }
        $list->delete();

        return 'deleted';
    }
}
