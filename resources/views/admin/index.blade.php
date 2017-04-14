@extends('master.main')

@section('main')

    <div class="col-lg-8">
        <div class="card-box">
            <div class="dropdown pull-right">
                <a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="false">
                    <i class="zmdi zmdi-more-vert"></i>
                </a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                </ul>
            </div>

            <h4 class="header-title m-t-0 m-b-30">Client Instances</h4>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Client Name</th>
                        <th>Domain</th>
                        <th>Schema</th>
                        <th>Users</th>
                        <th>Data Sets</th>
                        <th>Version</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($clients as $client)
                        <tr>
                            <td>{{$client->name}}</td>
                            <td>{{$client->domain}}</td>
                            <td>{{$client->schema}}</td>
                            <td>{{$client->info['user_count']}}</td>
                            <td>@if(isset($client->info['dataset_count'])) {{ $client->info['dataset_count'] }} @endif</td>
                            <td>
                                @if($client->version != null)
                                    {{$client->version->version}}
                                    [{{$client->version->versioned_at}}]
                                @endif
                            </td>
                            <td>
                                <a class="btn btn-primary btn-sm" href="{{action('ClientController@config', [$client->id])}}">Edit Config</a>
                            </td>

                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>

@stop

@push('js_footer')

@endpush

@push('style')


@endpush
