@extends('master.main')

@section('title', $dataset->name . ' Overview')

@section('main')

    <div class="bg-light lter b-b wrapper-md mb-10">
        <div class="row">
            <div class="col-sm-6 col-xs-12">
                <h1 class="font-thin h3 m-0"><strong>{{$dataset->name}}</strong> Overview</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <section class="boxs">
                <div class="boxs-header dvd dvd-btm">
                    <h1 class="custom-font">
                        Uploaders
                    </h1>
                    <ul class="controls">
                        @can('citynexus', ['datasets', 'upload'])
                            <li class="dropdown"> <a role="button" tabindex="0" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><i class="fa fa-plus"></i> Create New <i class="fa fa-angle-down ml-5"></i></a>
                                <ul class="dropdown-menu pull-right with-arrow animated littleFadeInUp">
                                    <li><a href="{{route('uploader.create')}}?dataset_id={{$dataset->id}}&type=csv"> CSV/Excel Uploader</a></li>
                                </ul>
                            </li>
                        @endcan
                    </ul>
                </div>
                <div class="boxs-body">
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
                                    <td><a href="{{route('uploader.show', [$uploader->id])}}" class="btn btn-primary btn-sm btn-raised">Settings</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-info">
                            <div class="alert-body">
                                You don't have any uploaders yet! Create your first one.
                                <a href="{{route('uploader.create')}}?dataset_id={{$dataset->id}}" class="btn btn-primary btn-raised">Create Uploader</a>
                            </div>
                        </div>
                    @endif
                </div>
            </section>
        </div>
    </div>
@endsection