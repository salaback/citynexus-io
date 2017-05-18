@extends('master.main')

@section('title', $user->fullname . ' Settings')

@section('main')

    <div class="col-md-9">
        <section class="boxs">
            <div class="boxs-header dvd dvd-btm">
                <h1 class="custom-font"><strong>{{$user->first_name}} {{$user->last_name}}</strong> Settings</h1>
            </div>

            <div class="boxs-body">
                <form class="form-horizontal" role="form">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="first_name" class="col-sm-4 control-label">First Name</label>
                                <div class="col-sm-8">
                                    <input type="text" name="user[first_name]" id="first_name" class="form-control" value="{{$user->first_name}}" title="" required="required" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="first_name" class="col-sm-4 control-label">Last Name</label>
                                <div class="col-sm-8">
                                    <input type="text" name="user[last_name]" id="last_name" class="form-control" value="{{$user->last_name}}" title="" required="required" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-sm-4 control-label">Email Address</label>
                                <div class="col-sm-8">
                                    <p class="form-control-static">{{$user->email}}</p>
                                    <span class="help-block mb-0">Only the user can change their email address.</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="department" class="col-sm-4 control-label">Department</label>
                                <div class="col-sm-8">
                                    <input type="text" name="department" id="department" class="form-control" value="{{$membership['department']}}" title=""  >
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="title" class="col-sm-4 control-label">Title</label>
                                <div class="col-sm-8">
                                    <input type="text" name="title" id="title" class="form-control" value="{{$membership['title']}}" title=""  >
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <h4>Groups</h4>
                            <div id="memberships">
                                <ul class="list-group">
                                    @foreach($groups as $group)
                                    <li class="list-group-item @if($user->isMember($group)) list-group-item-success @endif" id="group-{{$group->id}}">
                                        <span id="group-wait-{{$group->id}}" class="hidden"><i class="fa fa-spinner fa-spin"></i></span>
                                        <span id="group-add-{{$group->id}}" class="badge bg-green @if($user->isMember($group)) hidden @endif" style="cursor: pointer" onclick="addToGroup({{$group->id}})"> <i id="group-span-icon" class="fa fa-plus"></i> </span>
                                        <span id="group-remove-{{$group->id}}" class="badge bg-red  @unless($user->isMember($group)) hidden @endunless" style="cursor: pointer" onclick="removeFromGroup({{$group->id}})"> <i id="group-span-icon" class="fa fa-times"></i> </span>
                                        {{$group->name}}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>

@endsection

@push('scripts')

<script>
    var userId = {{$user->id}};
    var addToGroup = function(id)
    {
        $('#group-wait-' + id).removeClass('hidden');
        $.ajax({
            url: "{{route('group.addUser')}}",
            type: 'POST',
            data: {
                _token: "{{csrf_token()}}",
                user_id: userId,
                group_id: id
            },
            success: function () {
                toggleGroup(id)
            },
            error: function () {
                alert('Uh oh! Something went wrong.');
            }
        })
    };

    var removeFromGroup = function(id)
    {
        $('#group-wait-' + id).removeClass('hidden');
        $.ajax({
            url: "{{route('group.removeUser')}}",
            type: 'POST',
            data: {
                _token: "{{csrf_token()}}",
                user_id: userId,
                group_id: id
            },
            success: function () {
                toggleGroup(id)
            },
            error: function () {
                alert('Uh oh! Something went wrong.');
            }
        })
    };

    var toggleGroup = function(id)
    {
        $('#group-' + id).toggleClass('list-group-item-success');
        $('#group-add-' + id).toggleClass('hidden');
        $('#group-remove-' + id).toggleClass('hidden');
        $('#group-wait-' + id).addClass('hidden');
    }
</script>

@endpush