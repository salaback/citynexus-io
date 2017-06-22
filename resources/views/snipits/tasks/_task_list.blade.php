<div class="col-md-4 col-sm-6 col-xs-12">
    <div class="boxs">
        <div class="boxs-header">
            <h1 class="custom-font"> {{$list->name}} </h1>
        </div>
        <div class="boxs-body">
            <div class="list-group" id="tasks_{{$list->id}}">
                @foreach($list->tasks as $task)
                    @include('snipits.tasks._task')
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