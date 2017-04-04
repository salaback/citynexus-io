@extends('master.main')

@section('title', 'Address Sync')

@section('main')

    <div class="col-lg-offset-1 col-lg-10 animated" id="slide-card">
        <div class="card-box p-b-0">
            <div class="row">
                    <div>

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#unparsed" aria-controls="home" role="tab" data-toggle="tab">Unparsed Entity Name</a></li>
                            <li role="presentation"><a href="#parsed" aria-controls="profile" role="tab" data-toggle="tab">Parsed Address</a></li>
                            <li role="presentation"><a href="#property_id" aria-controls="profile" role="tab" data-toggle="tab">Property ID</a></li>

                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="unparsed">
                                <form action="{{action('UploaderController@post')}}" class="form-horizontal" method="post">
                                    {!! csrf_field() !!}
                                    <input type="hidden" name="slug" value="sync">
                                    <input type="hidden" name="sync[class]" value="entity">
                                    <input type="hidden" name="sync[type]" value="unparsed">
                                    <input type="hidden" name="uploader_id" value="{{$uploader->id}}">
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="full_address" class="col-sm-3 control-label">Full Entity Name</label>
                                            <div class="col-sm-4">
                                                <select name="sync[full_name]" class="form-control col-sm-9">
                                                    <option value="">Select Field</option>
                                                    @foreach($fields as $field)
                                                        <option value="{{$field['key']}}">{{$field['name']}} <{{$field['value']}}></option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="full_address" class="col-sm-3 control-label">Entity Role</label>
                                            <div class="col-sm-4">
                                                <input type="text" name="sync[role]" class="form-control col-sm-9">
                                            </div>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary waves-effect waves-light">Save Unparsed Entity Sync</button>
                                </form>
                            </div>

                            <div role="tabpanel" class="tab-pane" id="parsed">
                                <form action="{{action('UploaderController@post')}}" class="form-horizontal" method="post">
                                    {!! csrf_field() !!}
                                    <input type="hidden" name="slug" value="sync">
                                    <input type="hidden" name="sync[class]" value="entity">
                                    <input type="hidden" name="sync[type]" value="parsed">
                                    <input type="hidden" name="uploader_id" value="{{$uploader->id}}">
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="full_address" class="col-sm-3 control-label">Title</label>
                                            <div class="col-sm-4">
                                                <select name="sync[entity][title]" class="form-control col-sm-9">
                                                    <option value=""></option>
                                                    @foreach($fields as $field)
                                                        <option value="{{$field['key']}}">{{$field['name']}} <{{$field['value']}}></option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="first_name" class="col-sm-3 control-label">First Name</label>
                                            <div class="col-sm-4">
                                                <select name="sync[entity][first_name]" class="form-control col-sm-9">
                                                    <option value=""></option>
                                                    @foreach($fields as $field)
                                                        <option value="{{$field['key']}}">{{$field['name']}} <{{$field['value']}}></option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="middle_name" class="col-sm-3 control-label">Middle Name</label>
                                            <div class="col-sm-4">
                                                <select name="sync[entity][middle_name]" class="form-control col-sm-9">
                                                    <option value=""></option>
                                                    @foreach($fields as $field)
                                                        <option value="{{$field['key']}}">{{$field['name']}} <{{$field['value']}}></option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="last_name" class="col-sm-3 control-label">Last Name</label>
                                            <div class="col-sm-4">
                                                <select name="sync[entity][last_name]" class="form-control col-sm-9">
                                                    <option value=""></option>
                                                    @foreach($fields as $field)
                                                        <option value="{{$field['key']}}">{{$field['name']}} <{{$field['value']}}></option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="suffix" class="col-sm-3 control-label">Suffix</label>
                                            <div class="col-sm-4">
                                                <select name="sync[entity][suffix]" class="form-control col-sm-9">
                                                    <option value=""></option>
                                                    @foreach($fields as $field)
                                                        <option value="{{$field['key']}}">{{$field['name']}} <{{$field['value']}}></option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary waves-effect waves-light">Save Parsed Entity Sync</button>
                                </form>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>

@endsection