@extends('master.main')

@section('main')

    <div class="col-lg-8">
        <section class="boxs ">
            <div class="boxs-header dvd dvd-btm">
                <h1 class="custom-font"><strong>Client</strong> Instances</h1>

            </div>
            <div class="boxs-body p-0">
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
                                    {{$client->version_id ?: 0}}
                                </td>
                                <td>
                                    <div class="col-md-3 dropdown">
                                        <a href="#" class="btn btn-simple dropdown-toggle" data-toggle="dropdown">
                                            Actions
                                            <b class="caret"></b>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a href="{{route('admin.client.migrateDb', [$client->id])}}">Migrate</a></li>
                                            <li><a href="{{route('admin.client.upgrade', [$client->id])}}">Upgrade</a></li>
                                            <li><a href="{{route('admin.client.config', [$client->id])}}">Edit Config</a></li>
                                        </ul>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
    <div class="col-sm-4">
        <a href="{{route('client.create')}}" class="btn btn-raised btn-primary">Create New Client</a>
    </div>

@stop

@push('js_footer')

@endpush

@push('style')


@endpush
