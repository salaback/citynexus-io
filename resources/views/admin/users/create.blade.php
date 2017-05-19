@extends('master.main')

@section('title', 'Invite New User')

@section('main')

    <div class="col-md-9">
        <form class="form-horizontal" role="form" action="{{route('users.store')}}" method="post">
            {{csrf_field()}}
            <section class="boxs">
                <div class="boxs-header dvd dvd-btm">
                    <h1 class="custom-font"><strong>Invite</strong> New User</h1>
                </div>
                <div class="boxs-body">

                    @include('master._form_error')
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="first_name" class="col-sm-4 control-label">First Name</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="first_name" id="first_name" class="form-control" value="{{old('first_name')}}" title="" required="required" >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="first_name" class="col-sm-4 control-label">Last Name</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="last_name" id="last_name" class="form-control" value="{{old('last_name')}}" title="" required="required" >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="col-sm-4 control-label">Email Address</label>
                                    <div class="col-sm-8">
                                        <input type="email" id="email" name="email" value="{{old('email')}}" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="department" class="col-sm-4 control-label">Department</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="department" id="department" class="form-control" value="{{old('department')}}" title=""  >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="title" class="col-sm-4 control-label">Title</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="title" id="title" class="form-control" value="{{old('title')}}" title=""  >
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <h4>Groups</h4>
                                <div id="memberships" style="max-height: 500px; overflow: scroll">
                                    <div class="list-group">
                                        @foreach($userGroups->sortBy('name') as $group)
                                           <div class="list-group-item">
                                               <div class="checkbox">
                                                   <label>
                                                       <input type="checkbox" name="groups[]" value="{{$group->id}}">
                                                       {{$group->name}}
                                                   </label>
                                               </div>
                                           </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="boxs-footer">
                   <input type="submit" class="btn btn-raised btn-primary" value="Invite User">
                </div>
            </section>
        </form>
    </div>

@endsection

@push('scripts')

@endpush