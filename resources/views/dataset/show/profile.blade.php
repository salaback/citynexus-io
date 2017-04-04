@extends('master.main')

@section('title', "Data Set Settings: " . $dataset->name)

@section('main')

    <div class="col-md-6">
        <section class="boxs">
            <div class="boxs-header dvd dvd-btm">
                <h1 class="custom-font"><strong>Uploaders</strong></h1>
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
                                <td>@if($uploader->uploads->first()){{$uploader->uploads->first()->created_at->diffForHumans() }}@endif</td>
                                <td><a href="{{route('uploader.show', [$uploader->id])}}" class="btn btn-raised btn-primary btn-sm">Settings</a></td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                @else
                    <div class="alert alert-info">
                        <div class="alert-body">
                            You don't have any uploaders yet! Create your first one.
                            <a href="{{route('uploader.create')}}?dataset_id={{$dataset->id}}" class="btn btn-raised btn-primary">Create Uploader</a>
                        </div>
                    </div>
                @endif
            </div>
            <a href="{{route('uploader.create')}}?dataset_id={{$dataset->id}}" class="btn btn-raised btn-default btn-sm pull-right">Create New Uploader</a>
        </section>
    </div>
@endsection