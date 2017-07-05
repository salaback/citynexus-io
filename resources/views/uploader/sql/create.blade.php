@extends('master.main')

@section('title', 'Create SQL Uploader')

@section('main')
        <div class="boxs">
            <div class="boxs-header dvd dvd-btm">
                <h1 class="custom-font">
                    <strong>Create New</strong> SQL Uploader
                </h1>
            </div>
            <div class="boxs-body">
                <form action="{{route('uploader.store')}}" role="form" method="post">
                <div class="row">
                    <div class="col-md-6">
                            {{csrf_field()}}
                        <input type="hidden" name="dataset_id" value="{{$dataset->id}}">
                        <input type="hidden" name="type" value="sql">
                            <section class="boxs">
                                <div class="boxs-header dvd dvd-btm">
                                    <h1 class="custom-font"><strong>Uploader </strong>Info</h1>

                                </div>
                                <div class="boxs-body">

                                        <div class="form-group">
                                            <label for="name">Uploader Name</label>
                                            <input type="text" class="form-control" id="name" name="name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Uploader Description</label>
                                            <textarea type="password" class="form-control" id="description" rows="5" ></textarea>
                                        </div>
                                    <div class="form-group">
                                        <label for="frequency">Uploader Frequency</label>
                                        <select type="text" class="form-control" id="frequency">

                                            <option value="intermittent">Intermittent</option>
                                            <option value="hourly">Hourly</option>
                                            <option value="daily">Daily</option>
                                            <option value="weekly">Weekly</option>
                                            <option value="monthly">Monthly</option>
                                            <option value="quarterly">Quarterly</option>
                                            <option value="annually">Annually</option>
                                        </select>
                                    </div>

                                </div>
                                <div class="boxs-footer">
                                    <input id="createBtn" type="submit" class="btn btn-primary btn-raised" value="Create Uploader" disabled>
                                </div>
                            </section>

                    </div>
                    <div class="col-md-6">
                        <div id="sql_settings">
                        <div class="alert alert-info">
                            Before you start uploading, make sure your file is in the correct format for use in CityNexus.
                            Each data set must have a header row with unique headers, and each row should have some sort
                            of identifying information like an address, property id, or lot number.
                        </div>
                            <div class="form-horizontal" >
                                <div class="form-group">
                                    <label for="driver" class="control-label col-sm-4">Database Type</label>
                                    <div class="col-sm-8">
                                        <select name="settings[db][driver]" id="driver" class="form-control"required>
                                            <option value="">Select One</option>
                                            <option value="MySQL">MySQL</option>
                                            <option value="pgsql">PostgreSQL</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="host" class="control-label col-sm-4">Database Host</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="settings[db][host]" id="db_host" placeholder="192.0.2.1" required >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="database" class="control-label col-sm-4">Database Name</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="settings[db][database]" id="db_name" placeholder="dn_name_1234" required >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="username" class="control-label col-sm-4">Database User Name</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="settings[db][username]" id="db_user" placeholder="user" >
                                        <span class="help-block mb-0">If this is a public database the user name is optional.</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="username" class="control-label col-sm-4">Database Password</label>
                                    <div class="col-sm-8">
                                        <input type="password" class="form-control" name="settings[db][password]" id="db_password" >
                                        <span class="help-block mb-0">If this is a public database the password is optional.</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="table" class="control-label col-sm-4">Database Table Name</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="settings[table]" placeholder="properties_table" id="db_table" required value="tabler_police_calls">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="port" class="control-label col-sm-4">Database Port</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="settings[db][port]" id="db_port" placeholder="1433" >
                                        <span class="help-block mb-0">Database port is optional.</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="port" class="control-label col-sm-4">Database Schema</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="settings[db][schema]" id="db_schema" value="public" >
                                        <span class="help-block mb-0">Database is only required for PostgreSQL.</span>
                                    </div>
                                </div>
                                <input type="hidden" name="settings[db][prefix]" value="">
                                <input type="hidden" name="settings[db][charset]" value="utf8">
                            </div>
                        <div class="btn btn-primary btn-raised" onclick="testSql()" id="testSql">Test SQL Connection</div>
                        </div>
                        <div class="form-horizontal hidden" id="table_settings">
                            <div class="col-sm-12">
                                <div class="alert alert-info">
                                    <h4>
                                        Successfully connected to data table!
                                    </h4>
                                    <p>
                                        Now please select a primary key and time stamp from the available columns.
                                        Selecting these fields is critical to keeping synced with the database
                                    </p>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="frequency">Choose Unique ID</label>
                                    <select type="text" class="form-control" name="settings[unique_id]" id="primaryKey">

                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="frequency">Choose Creation Time Stamp</label>
                                    <select type="text" class="form-control" name="settings[created_at]" id="timeStamp">

                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="frequency">Choose Modification Time Stamp</label>
                                    <select type="text" class="form-control" name="settings[updated_at]" id="updatedTimeStamp">

                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </form>

            </div>
            </div>
        </div>

@endsection


@push('style')

@endpush

@push('scripts')
<script>
    var testSql = function() {
        $('#testSql').html('<i class="fa fa-spin fa-spinner"></i> Connecting');
        event.preventDefault();
        var settings = {
            driver: $('#driver').val(),
            host: $('#db_host').val(),
            database: $('#db_name').val(),
            username: $('#db_user').val(),
            password: $('#db_password').val(),
            port: $('#db_port').val(),
            charset:  'utf8',
            prefix: '',
            schema: 'public',
        };

        $.ajax({
            url: "{{route('uploader.sqlTest')}}",
            type: "POST",
            data: {
                _token: "{{csrf_token()}}",
                settings: settings,
                table: $('#db_table').val()
            },
            success: function (data) {
                test = data;
                alert('success', 'SQL Test Successful');
                var selects = '<option>Select One</option>';
                for(var i = 0; i < data.length; i++)
                {
                   selects += '<option value="' + data[i].column_name + '" ';
                   if(data[i].column_name == 'id') selects += 'selected';
                   selects += '>' + data[i].column_name + '</option>';
                };

                $('#primaryKey').html(selects);

                selects = '<option>Select One</option>';

                for(var i = 0; i < data.length; i++)
                {
                    selects += '<option value="' + data[i].column_name + '" ';
                    if(data[i].column_name == 'created_at') selects += 'selected';
                    selects += '>' + data[i].column_name + '</option>';
                };

                $('#timeStamp').html(selects);

                selects = '<option>Select One</option>';

                for(var i = 0; i < data.length; i++)
                {
                    selects += '<option value="' + data[i].column_name + '" ';
                    if(data[i].column_name == 'updated_at') selects += 'selected';
                    selects += '>' + data[i].column_name + '</option>';
                };

                $('#updatedTimeStamp').html(selects);

                $('#sql_settings').addClass('hidden');
                $('#table_settings').removeClass('hidden');
                $('#createBtn').attr('disabled', false);

            },
            error: function(data) {
                alert('warning', JSON.stringify(data));
            }
        })
    }
</script>
@endpush