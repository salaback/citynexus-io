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
                <div class="row">
                    <div class="col-md-6">
                        <form action="{{route('uploader.store')}}" role="form" method="post">
                            {{csrf_field()}}
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
                                            <option value="interment">Interment</option>
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
                                    <span id="uploadFirst" class="alert alert-info">Test SQL connection before saving.</span>
                                </div>
                            </section>
                        </form>

                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-info">
                            Before you start uploading, make sure your file is in the correct format for use in CityNexus.
                            Each data set must have a header row with unique headers, and each row should have some sort
                            of identifying information like an address, property id, or lot number.
                        </div>
                            <div class="form-horizontal" id="sql_settings">
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
                                        <input type="text" class="form-control" name="settings[db][host]" placeholder="192.0.2.1" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="database" class="control-label col-sm-4">Database Name</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="settings[db][database]" placeholder="dn_name_1234" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="username" class="control-label col-sm-4">Database User Name</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="settings[db][username]" placeholder="user">
                                        <span class="help-block mb-0">If this is a public database the user name is optional.</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="username" class="control-label col-sm-4">Database Password</label>
                                    <div class="col-sm-8">
                                        <input type="password" class="form-control" name="settings[db][password]">
                                        <span class="help-block mb-0">If this is a public database the password is optional.</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="table" class="control-label col-sm-4">Database Table Name</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="settings[table]" placeholder="properties_table" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="port" class="control-label col-sm-4">Database Port</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="settings[db][port]" placeholder="1433">
                                        <span class="help-block mb-0">Database port is optional.</span>
                                    </div>
                                </div>
                            </div>
                        <button class="btn btn-primary btn-raised">Test SQL Connection</button>

                    </div>
                    </div>
                </div>
            </div>
        </div>

@endsection


@push('style')

@endpush

@push('scripts')

@endpush