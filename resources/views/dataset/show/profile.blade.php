@extends('master.main')

@section('title', "Data Set Settings: " . $dataset->name)

@section('main')
    <div class="col-sm-12">
        <h4>Data Set: {{$dataset->name}}</h4>
    </div>
    <div class="col-md-6">
        <section class="boxs">
            <div class="boxs-header dvd dvd-btm">
                <h1 class="custom-font"><strong>Uploaders</strong></h1>
            </div>
            <div class="boxs-body">
            @if($dataset->uploaders->count() > 0)
                @php($uploaders = $dataset->uploaders()->orderBy('updated_at', "DEC")->paginate(5))
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
                        @foreach($uploaders as $uploader)

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
                {{$uploaders->links()}}
            </div>
            <a href="{{route('uploader.create')}}?dataset_id={{$dataset->id}}" class="btn btn-raised btn-default btn-sm pull-right">Create New Uploader</a>
        </section>
    </div>

    <div class="col-md-6">
        <section class="boxs">
            <div class="boxs-header dvd dvd-btm">
                <h1 class="custom-font"><strong>Data Fields</strong></h1>
            </div>
            <div class="boxs-body">
                @if(count($dataset->schema) > 0)
                    <table class="table m-b-0">
                        <thead>
                        <tr>
                            <th>Visible</th>
                            <th>Field Name</th>
                            <th>Key</th>
                            <th>Type</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($dataset->schema as $item)
                            <tr class="">
                                <td>@if(isset($item['show']) && $item['show'] =='on') <i class="fa fa-check"></i> @endif</td>
                                <td>{{$item['name']}}</td>
                                <td>{{$item['key']}}</td>
                                <td>{{$item['type']}}</td>
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
        </section>
    </div>
    @if(count($dataset->schema) > 0)
        <div class="col-sm-12">
        <div class="row">
            <div class="col-md-12">
                <section class="boxs ">
                    <div class="boxs-header dvd dvd-btm">
                        <h1 class="custom-font"><strong>{{$dataset->name}}</strong> Data</h1>
                    </div>
                    <div class="boxs-body">
                        <table id="datasets" class="table table-custom">
                            <thead>
                            <tr>
                                <th>Time Stamp</th>
                                @foreach($dataset->schema as $element)
                                    @if($element['show'] == 'on')
                                    <th>{{$element['name']}}</th>
                                    @endif
                                @endforeach
                            </tr>
                            </thead>
                        </table>
                    </div>
                </section>
            </div>
        </div>
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

    <script>
        $(function() {
            $("#datasets").DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{route('dataset.rawData')}}',
                columns: [
                    {data: '__profile', name: '__profile'},
                    {data: '__created_at', name: '__created_at'},
                    @foreach($dataset->schema as $element)
                        @if($element['show'] == 'on')
                            {data: '{{$element['key']}}', name: '{{$element['key']}}'},@endif
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