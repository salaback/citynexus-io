@extends('master.main')

@section('title', 'Client Admin')

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
                            <tr id="client-{{$client->id}}">
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
                                            <li><a href="{{route('admin.client.migrateDb', [$client->id])}}">Migrate from CityNexus 1.0</a></li>
                                            <li><a href="{{route('admin.client.migrateDb', [$client->id])}}">Migrate</a></li>
                                            <li><a href="{{route('admin.client.upgrade', [$client->id])}}">Upgrade</a></li>
                                            <li><a href="{{route('admin.client.config', [$client->id])}}">Edit Config</a></li>
                                            <li><a href="#" onclick="destroyClient({{$client->id}}, '{{$client->name}}')">Destroy Client</a></li>
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

@push('modal')

<a class="btn btn-primary" data-toggle="modal" href="modal-id">Trigger modal</a>
<div class="modal fade" id="modal-id">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Enter CityNexus 1.0 Credentials</h4>
			</div>
			<div class="modal-body">
				<form action="" method="POST" role="form">
					<legend>Credentials</legend>

					<div class="form-group">
						<label for=""></label>
						<input type="text" class="form-control" name="" id="" placeholder="Input...">
					</div>



					<button type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@push('scripts')

<script>
    var destroyClient = function (clientId, clientName)
    {
        if(confirm('Are you sure you want to delete this ' + clientName + '? This action can not be undone.'))
        {
            $.ajax({
                url: "{{route('client.index')}}/" + clientId,
                type: "POST",
                data: {
                    _token: "{{csrf_token()}}",
                    _method: "DELETE"
                },
                success: function() {
                    $("#client-" + clientId).hide();
                },
                error: function () {
                    "Uh oh!  Something went wrong."
                }
            })
        }
    }
</script>

@endpush

@push('style')


@endpush
