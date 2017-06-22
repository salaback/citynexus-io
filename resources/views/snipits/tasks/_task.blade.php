<div class="list-group-item @if(isset($task->due_at) && $task->due_at->isPast()) overdue @endif " id="task_wrapper_{{$task->id}}">
    <div class="checkbox">
        <i class="pull-right fa fa-edit" onclick="editTask({{$task->id}})"></i>
        <label>
            <input type="checkbox" name="datapointPropertyRange" id="task_{{$task->id}}" onclick="completeTask({{$task->id}})">
        </label>
        {{$task->name}}

        @if(isset($task->due_at))
            <div class="task-due">
                Due: {{$task->due_at->diffForHumans()}}
            </div>
        @endif
        @if(isset($task->assigned_to))
            <div class="assigned-to">
                Assigned to: {{$task->assignee->fullname}}
            </div>
        @endif

        @if(isset($task->body))
            <div class="task-detail">
                {{str_limit($task->body, 100, '...')}}
            </div>
        @endif
    </div>
</div>