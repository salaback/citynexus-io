@extends('master.main')


@section('main')

    <form action="{{route('client.store')}}" method="POST" class="form-horizontal" role="form">
        {{csrf_field()}}
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="panel-title">Create Client</span>
            </div>
            <div class="panel-body">
                <h4>Client Information</h4>
                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Client Name</label>
                    <div class="col-sm-10">
                        <input type="text" name="client[name]" id="inputID" class="form-control" value="" title="" required="required" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="domain" class="col-sm-2 control-label">Domain</label>
                    <div class="col-sm-10">
                        <div class="input-group">
                            <div class="input-group-addon">https://</div>
                            <input type="text" class="form-control" id="" name="client[domain]" placeholder="subdomain">
                            <div class="input-group-addon">.citynexus.io</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel-body">
                <h4>Primary Account Owner</h4>
                <div class="form-group">
                    <label for="first_name" class="col-sm-2 control-label">First Name</label>
                    <div class="col-sm-10">
                        <input type="text" name="user[first_name]" id="first_name" class="form-control" value="" title="" required="required" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="lName" class="col-sm-2 control-label">Last Name</label>
                    <div class="col-sm-10">
                        <input type="text" name="user[last_name]" id="last_name" class="form-control" value="" title="" required="required" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" name="user[email]" id="email" class="form-control" value="" title="" required="required" >
                    </div>
                </div>

            </div>
            <div class="panel-footer">
                <button type="submit" class="btn btn-raised btn-primary">Create Client</button>
            </div>
        </div>
    </form>

@endsection