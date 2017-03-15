<div class="col-md-9">
    <table class="table table-hover">
    	<thead>
    		<tr>
    			<th>Client</th>
                <th>Domain</th>
                <th>Last Migration</th>
                <th></th>
    		</tr>
    	</thead>
    	<tbody>
            @foreach($clients as $client)
    		<tr>
    			<td>{{$client->name}}</td>
                <td>{{$client->domain}}</td>
                <td>{{$client->migrated_at->diffForHumans()}}</td>
                <td>
                    <button onclick="resetDb({{$client->id}})" class="btn btn-primary btn-sm">Reset DB</button>
                    <button onclick="importId = {{$client->id}}; $('#importDb').modal('show')" class="btn btn-primary btn-sm">Import DB</button>
                    <button onclick="migrateDb({{$client->id}})" class="btn btn-primary btn-sm">Migrate DB</button>
                    <a href="{{route('admin.client.config', [$client->id])}}" class="btn btn-primary btn-sm">Config</a>

                </td>
    		</tr>
            @endforeach
    	</tbody>
    </table>
</div>

<div class="col-sm-3">
    <a href="{{route('admin.client.create')}}" class="btn btn-primary pull-right" >Create New Client</a>
</div>

<!-- Modal -->
<div class="modal fade" id="user-settings-modal" tabindex="-1" role="dialog" aria-labelledby="permissions">
    <form id="user-settings-form" action="/{{config('citynexus.root_directory')}}/settings/settings-update" method="post">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="permissions_title">User Settings</h4>
            </div>
            <div class="modal-body" id="user-settings">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-primary" value="Save Permissions" />
            </div>
        </div>
    </div>
    </form>
</div>

<!-- sample modal content -->
<div id="importDb" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Import from Database</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="form-group">
                    	<label for="dbHost" class="col-sm-2 control-label">DB Host</label>
                    	<div class="col-sm-10">
                    		<input type="text" name="dbHost" id="dbHost" class="form-control" value="" title="" required="required" >
                    	</div>
                    </div>
                    <div class="form-group">
                        <label for="dbName" class="col-sm-2 control-label">DB Name</label>
                        <div class="col-sm-10">
                            <input type="text" name="dbName" id="dbName" class="form-control" value="" title="" required="required" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="dbUser" class="col-sm-2 control-label">DB Username</label>
                        <div class="col-sm-10">
                            <input type="text" name="dbUser" id="dbUser" class="form-control" value="" title="" required="required" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="dbPassword" class="col-sm-2 control-label">DB Password</label>
                        <div class="col-sm-10">
                            <input type="password" name="dbPassword" id="dbPassword" class="form-control" value="" title="" required="required" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="dbSchema" class="col-sm-2 control-label">DB Schema</label>
                        <div class="col-sm-10">
                            <input type="text" name="dbSchema" id="dbSchema" class="form-control" value="" title="" required="required" >
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary waves-effect waves-light" onclick="importDb()">Import Database</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


@push('js_footer')

<script>
    var importId;

    var resetDb = function(id)
    {
        if(confirm('Are you sure you want to reset this database? This can\'t be undone.'))
        {
            $.get('/admin/client/reset-db/' + id).done(function(){
                Command: toastr["success"]('Database reset.');
            });
        }
    };

    var migrateDb = function(id)
    {
        if(confirm('Are you sure you want to migrate this database?'))
        {
            $.get('/admin/client/migrate-db/' + id).done(function(){
                Command: toastr["success"]('Database migrated.');
            });
        }
    }

    var importDb = function()
    {
        $('#importDb').modal('hide');

        $.ajax({
            url: '/admin/client/import-db/',
            type: 'POST',
            data: {
                _token: "{{csrf_token()}}",
                import_id: importId,
                host: $("#dbHost").val(),
                name: $("#dbName").val(),
                user: $("#dbUser").val(),
                password: $("#dbPassword").val(),
                schema: $("#dbSchema").val(),
            }
        }).done(function(){
            Command: toastr["success"]('Database imported.');
        });
    }
</script>
@endpush