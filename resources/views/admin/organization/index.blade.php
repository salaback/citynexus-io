@extends('master.main')

@section('main')

    <div class="col-md-9">
        <section class="boxs ">
            <div class="boxs-header dvd dvd-btm">
                <h1 class="custom-font"><strong>Organization</strong> Users</h1>

            </div>
            <div class="boxs-body p-0">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Title</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{$user->fullname}}</td>
                            <td>{{$user->department}}</td>
                            <td>{{$user->title}}</td>
                            <td><a href="{{route('admin.user.edit', [$user->id])}}" class="btn btn-raised btn-primary btn-sm">Manage</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>

@endsection