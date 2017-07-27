<div class="list-group-item completed" id="task_wrapper_{{$task->id}}" style="background-color: lightgrey">
        {{$task->name}}

            <div class="task-due">
                Completed: {{$task->deleted_at->diffForHumans()}}
            </div>
        @if(isset($task->due_at))
            <div class="task-due">
                Due: {{$task->due_at->diffForHumans()}}
            </div>
        @endif
            <div class="assigned-to">
                Completed by: {{$task->completee->fullname}}
            </div>
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