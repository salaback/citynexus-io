@extends('master.main')

@section('title', 'Organization Settings')

@section('main')
    <div class="row">
        <div class="col-md-6">
            <section class="boxs ">
                <div class="boxs-header dvd dvd-btm">
                    <h1 class="custom-font"><strong>Organization</strong> Users</h1>
                    @can('citynexus', ['org-admin', 'users-create'])
                        <ul class="controls">
                            @can('citynexus', ['org-admin', 'users-create'])
                            <li><a href="{{route('users.create')}}"><i class="fa fa-plus mr-5"></i> Invite New User</a></li>
                            @endcan

                        </ul>
                    @endcan
                </div>
                <div class="boxs-body p-0" style="max-height: 400px; overflow: scroll;">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Title</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody >
                        @foreach($users->sortBy('last_name') as $user)
                            <tr>
                                <td>{{$user->fullname}}</td>
                                <td>{{$user->info->department}}</td>
                                <td>{{$user->info->title}}</td>
                                <td><a href="{{route('users.edit', [$user->id])}}" class="btn btn-raised btn-primary btn-sm">Manage</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
        <div class="col-md-6">
            <section class="boxs ">
                <div class="boxs-header dvd dvd-btm">
                    <h1 class="custom-font"><strong>Organization</strong> Groups</h1>
                    @can('citynexus', ['org-admin', 'groups'])
                        <ul class="controls">
                            @can('citynexus', ['org-admin', 'groups'])
                                <li><a href="{{route('groups.create')}}"><i class="fa fa-plus mr-5"></i> Create New Group</a></li>
                            @endcan

                        </ul>
                    @endcan
                </div>
                <div class="boxs-body p-0">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Users</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($groups->sortBy('name') as $group)
                            <tr>
                                <td>{{$group->name}}</td>
                                <td>{{$group->userCount}}</td>
                                <td><a href="{{route('groups.edit', [$group->id])}}" class="btn btn-raised btn-primary btn-sm">Manage</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>


@endsection