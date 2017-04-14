@extends('master.main')


@section('main')

    <form action="{{action('ClientController@store')}}" method="POST" class="form-horizontal" role="form">
        {{csrf_field()}}
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="panel-title">Create Client</span>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Client Name</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" id="inputID" class="form-control" value="" title="" required="required" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="domain" class="col-sm-2 control-label">Domain</label>
                    <div class="col-sm-10">
                        <div class="input-group">
                            <div class="input-group-addon">https://</div>
                            <input type="text" class="form-control" id="exampleInputAmount" name="domain" placeholder="subdomain">
                            <div class="input-group-addon">.citynexus.io</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <button type="submit" class="btn btn-primary">Create Client</button>
            </div>
        </div>
    </form>

@endsection