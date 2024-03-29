@extends('master.main')

@section('title', $dataset->name . ' Overview')

@section('main')

    <div class="bg-light lter b-b wrapper-md mb-10">
        <div class="row">
            <div class="col-sm-6 col-xs-12">
                <h1 class="font-thin h3 m-0"><strong>{{$dataset->name}}</strong> Overview</h1>
            </div>
            @can('citynexus', ['datasets', 'delete'])
                <div class="col-sm-6">
                    <form action="{{route('dataset.destroy', [$dataset->id])}}" method="post">
                        {{csrf_field()}}
                        {{method_field('delete')}}
                    <button class="pull-right btn btn-danger btn-sm btn-raised"> <i class="fa fa-trash"></i> Remove Data Set</button>
                    </form>
                </div>
            @endcan
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6" style="max-height: 400px; overflow: scroll">
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
                                    <li><a href="{{route('uploader.create')}}?dataset_id={{$dataset->id}}&type=sql"> SQL Uploader</a></li>
                                    <li><a href='https://www.dropbox.com/oauth2/authorize?response_type=code&client_id=yn3kwol8tef5ozi&redirect_uri=http://localhost:8000/response/dropbox&state={"client_id":"{{config('client.id')}}","dataset_id":"{{$dataset->id}}","created_by":"{{\Illuminate\Support\Facades\Auth::id()}}"}'>
                                        Drop Box
                                        </a>
                                    </li>
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
                                    <td>
                                        @if($uploader->uploads->count() > 0)
                                            {{$uploader->uploads->first()->created_at->diffForHumans()}}
                                        @endif
                                    </td>
                                    <td><a href="{{route('uploader.show', [$uploader->id])}}" class="btn btn-primary btn-sm btn-raised">Settings</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-info">
                            <div class="alert-body">
                                You don't have any uploaders yet! Create your first one.
                            </div>
                        </div>
                    @endif
                </div>

            </section>
        </div>
        {{--<div class="col-sm-6" style="max-height: 400px; overflow: scroll">--}}
            {{--<section class="boxs">--}}
                {{--<div class="boxs-header dvd dvd-btm">--}}
                    {{--<h1 class="custom-font">--}}
                        {{--Data Fields--}}
                    {{--</h1>--}}
                {{--</div>--}}
                {{--<div class="boxs-body">--}}
                    {{--<table class="table m-b-0">--}}
                        {{--<thead>--}}
                        {{--<tr>--}}
                            {{--<th>Visible</th>--}}
                            {{--<th>Field Name</th>--}}
                            {{--<th>Key</th>--}}
                            {{--<th>Type</th>--}}
                        {{--</tr>--}}
                        {{--</thead>--}}
                        {{--<tbody>--}}
                        {{--@if(count($dataset->schema) > 0)--}}
                        {{--@foreach($dataset->schema as $item)--}}
                            {{--<tr class="">--}}
                                {{--<td>@if(isset($item['show']) && $item['show'] =='on') <i class="fa fa-check"></i> @endif</td>--}}
                                {{--<td>{{$item['name']}}</td>--}}
                                {{--<td>{{$item['key']}}</td>--}}
                                {{--<td>{{$item['type']}}</td>--}}
                            {{--</tr>--}}
                        {{--@endforeach--}}
                            {{--@endif--}}
                        {{--</tbody>--}}
                    {{--</table>--}}
                {{--</div>--}}
            {{--</section>--}}
        {{--</div>--}}

    @if(count($dataset->schema) > 0)
        <div class="col-md-12">
            <section class="boxs ">
                <div class="boxs-header dvd dvd-btm">
                    <h1 class="custom-font"><strong>{{$dataset->name}}</strong> Data</h1>
                </div>
                <div class="boxs-body">
                    <table id="rawdata" class="table table-custom">
                        <thead>
                        <tr>
                            <th>Profile</th>
                            <th>Time Stamp</th>
                            @foreach($dataset->schema as $element)
                                @if(isset($element['show']) && $element['show'] == 'on')
                                    <th>{{$element['name']}}</th>
                                @endif
                            @endforeach
                        </tr>
                        </thead>
                    </table>
                </div>
            </section>
        </div>
    @endif
@endsection
@push('style')

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">

@endpush

@push('scripts')

@if(count($dataset->schema) > 0)
    <script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>

    @php

    @endphp

    <script>
        $(function() {
            $("#rawdata").DataTable({
                processing:     true,
                serverSide:     true,
                scrollY:        400,
                scrollX:        true,
                deferRender:    true,
                scroller:       true,
                ajax: {
                    url: '{{route('dataset.rawData', [$dataset->id])}}',
                    data: function(d) {d._token = '{{csrf_token()}}'},
                    type: 'post'
                },
                columns: [
                        {data: "__profile", name: "__profile"},
                        {data: "__created_at", name: "__created_at"},
                    @foreach($dataset->schema as $element)
                        @if(isset($element['show']) && $element['show'] == 'on')
                        {data: "{{$element['key']}}", name: "{{$element['key']}}"},
                        @endif
                    @endforeach
                ],
                dom: "Bfrtip",
                buttons: [{extend: "copy", className: "btn-sm"}, {extend: "csv", className: "btn-sm"}, {
                    extend: "excel",
                    className: "btn-sm"
                }, {extend: "pdf", className: "btn-sm"}, {extend: "print", className: "btn-sm"}],

            });
        });
    </script>

@endif
@endpush