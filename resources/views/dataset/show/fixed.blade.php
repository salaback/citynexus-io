@extends('master.main')

@section('title', "Data Set Settings: " . $dataset->name)

@section('main')

    <div class="col-lg-offset-1 col-lg-10">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="dropdown pull-right">
                    <a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="false">
                        <i class="zmdi zmdi-more-vert"></i>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li href="{{route('uploader.create')}}?dataset_id={{$dataset->id}}">Create Uploader</li>
                    </ul>
                </div>

                <h4 class="header-title m-t-0 m-b-30">Uploaders</h4>
                @if($dataset->uploaders->count() > 0)
                <table class="table m-b-0">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Last Upload</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($dataset->uploaders as $uploader)
                        <tr class="">
                            <td>{{$uploader->name}}</td>
                            <td>{{ucwords($uploader->type)}}</td>
                            <td>{{$uploader->uploads->first()->created_at->diffForHumans()}}</td>
                            <td><a href="{{route('uploader.show', [$uploader->id])}}" class="btn btn-primary btn-sm">Settings</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @else
                <div class="alert alert-info">
                    <div class="alert-body">
                        You don't have any uploaders yet! Create your first one.
                        <a href="{{route('uploader.create')}}?dataset_id={{$dataset->id}}" class="btn btn-primary">Create Uploader</a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection