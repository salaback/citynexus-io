<div class="col-md-4 col-sm-6 col-xs-12" class="list-wrapper" id='list_wrapper_{{$list->id}}'>
    <div class="boxs">
        <div class="boxs-header">
            <h1 class="custom-font">
            @if($model_id != $list->taskable_id && $model_type != $list->taskable_type)

                @if($list->taskable_type == 'App\PropertyMgr\Model\Property' && $list->property->unit != null)
                        <a href="{{route('properties.show', [$list->taskable_id])}}">Unit {{$list->property->unit}}</a> >
                @elseif($model_type == 'App\\\PropertyMgr\\\Model\\\Entity')
                    <a href="{{route('properties.show', [$list->taskable_id])}}">{{$list->property->oneLineAddress}}</a> >
                @endif
            @endif
                {{$list->name}} </h1>
                <ul class="controls">
                        <li> <a href="#" onclick="hideList({{$list->id}})" role="button"><i class="fa fa-times"></i> Hide List</a></li>
                </ul>


        <div class="boxs-body list-body">
            <div class="list-group" class='tasks-well' id="tasks_{{$list->id}}">
                @foreach($list->tasks as $task)
                    @include('snipits.tasks._task')
                @endforeach
            </div>
            <div class="list-group" class='tasks-well' id="completed_tasks_{{$list->id}}">
                @foreach($list->completedTasks as $task)
                    @include('snipits.tasks._completed_task')
                @endforeach
            </div>
        </div>
        <div class="boxs-footer">
            <div class="col-sm-12">
                <button class="btn btn-sm  btn-primary btn-raised" onclick="getTaskForm({{$list->id}})">
                    <i class="fa fa-plus"></i> Add Task
                </button>
            </div>
            <div class="row"></div>
        </div>
    </div>
</div>