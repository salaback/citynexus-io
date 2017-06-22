<div class="row">
    <span id="lists">
        @foreach($lists as $list)
            @include('snipits.tasks._task_list')
        @endforeach
    </span>
    <div class="col-md-4 col-sm-6">
        <button class="btn btn-primary btn-raised" data-toggle="modal" data-target="#createListModal">
            Create New List
        </button>
    </div>
</div>

@push('modal')
<div class="modal" id="createListModal" tabindex="-1" role="dialog" aria-labelledby="createListModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create New List</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="list_name">
                        List Name
                    </label>
                    <input type="text" class="form-control" name="list_name" id="list_name">
                </div>
            </div>

            <div class="model-footer">
                <div class="col-sm-12">
                    <button class="btn btn-primary btn-raised" onclick="createList()">Create List</button>
                </div>
                <div class="row"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="createTaskModal" tabindex="-1" role="dialog" aria-labelledby="createTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create New Task</h4>
            </div>
            <input type="hidden" id="new_task_list_id">

            <div class="modal-body form-horizontal">
                <div class="form-group">
                    <label class="control-label col-sm-4" for="task_name">
                        Task Name
                    </label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="task_name" id="task_name">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4" for="task_description">
                        Task Description
                    </label>
                    <div class="col-sm-8">
                        <textarea class="form-control" name="task_description" id="task_description"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4" for="task_owner">
                        Task Owner
                    </label>
                    <div class="col-sm-8">
                        <select name="task_description" id="task_owner" style="width: 100%">
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4" for="task_owner">
                        Due Date
                    </label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="task_due_data" id="task_due_date">

                    </div>
                </div>
            </div>
            <div class="model-footer">
                <div class="col-sm-12">
                    <button class="btn btn-primary btn-raised" onclick="createTask()">Create Task</button>
                </div>
                <div class="row"></div>
            </div>
        </div>
    </div>
</div>

@endpush

@push('style')

<style>
    .overdue {
        background-color: #ffbcb5;
        color: red; !important
    }

    .task-detail {
        font-size: 12px;
        padding-top: 4px;
    }
    .task-due {
        font-size: 12px;
        padding-top: 4px;
        color: #707070;
    }
    .overdue .task-due {
        color: red !important;
        font-weight: 400;
        font-size: 14px;
    }
    .assigned-to {
        font-size: 12px;
        color: #707070;
    }
    .fa-edit {
        cursor: pointer;
    }
</style>
@endpush

@push('scripts')
<script>

    $.ajax({
        url: "{{route('users.index')}}?use=select2",
        success: function(data) {
            $('#task_owner').select2({
                placeholder: "Select an assignee...",
                allowClear: true,
                data: data
            });
            $("#task_owner").val({{\Illuminate\Support\Facades\Auth::id()}}).trigger('change');
        }
    });


    function createList()
    {
        $('#createListModal').modal('hide');

        $.ajax({
            url: "{{route('list.store')}}",
            method: "post",
            data: {
                name: $('#list_name').val(),
                taskable_type: "{{$model_type}}",
                taskable_id: {{$model_id}}
            },
            success: function (data) {
                $('#lists').append(data);
            },
            error: function (data) {
                alert(JSON.stringify(data))
            }
        })
    }

    function getTaskForm(id) {
        $('#new_task_list_id').val(id);
        $("#task_owner").val({{\Illuminate\Support\Facades\Auth::id()}}).trigger('change');
        $('#createTaskModal').modal('show');
    }

    $( function() {
        var dateFormat = "mm/dd/yy",
                from = $("#task_due_date")
                        .datepicker({
                            defaultDate: "+1w",
                            changeMonth: true,
                            numberOfMonths: 2
                        })
                        .on("change", function () {
                            to.datepicker("option", "minDate", getDate(this));
                        });
        });

    function createTask() {
        $('#createTaskModal').modal('hide');
        $.ajax({
            url: "{{route('task.store')}}",
            method: 'Post',
            data: {
                task_list_id: $('#new_task_list_id').val(),
                name: $('#task_name').val(),
                body: $('#task_description').val(),
                assigned_to: $('#task_owner').val(),
                due_at: $('#task_due_date').val(),
                created_by: {{\Illuminate\Support\Facades\Auth::id()}}
            },
            success: function(data){
                $('#tasks_' + $('#new_task_list_id').val()).append(data);
                $('#task_name').val(null);
                $('#task_description').val(null);
            },
            error: function (data){
                alert('warning', JSON.stringify(data));
            }
        })
    }

    function completeTask(id) {
        setTimeout(function(){
            $('#task_wrapper_' + id).fadeOut();
            $.ajax({
                url: "{{route('task.index')}}/" + id,
                type: 'POST',
                data: {
                    _method: "DELETE"
                },
                success: function() {
                    $('#task_wrapper_' + id).remove();
                },
                error: function(error) {
                    $('#task_wrapper_' + id).fadeIn();
                    alert('warning', JSON.stringify(error));
                },
            })
        }, 500)
    }
</script>
@endpush