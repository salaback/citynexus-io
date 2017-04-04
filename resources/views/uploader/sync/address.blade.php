@extends('master.main')

@section('title', 'Address Sync')

@section('main')

    <div class="col-lg-offset-1 col-lg-10 animated" id="slide-card">
        <div class="card-box p-b-0">
            <div class="row">
                    <div>

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#unparsed" aria-controls="home" role="tab" data-toggle="tab">Unparsed Address</a></li>
                            <li role="presentation"><a href="#parsed" aria-controls="profile" role="tab" data-toggle="tab">Parsed Address</a></li>
                            <li role="presentation"><a href="#property_id" aria-controls="profile" role="tab" data-toggle="tab">Property ID</a></li>

                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="unparsed">
                                <form action="{{action('UploaderController@post')}}" class="form-horizontal" method="post">
                                    {!! csrf_field() !!}
                                    <input type="hidden" name="slug" value="sync">
                                    <input type="hidden" name="sync[class]" value="address">
                                    <input type="hidden" name="sync[type]" value="unparsed">
                                    <input type="hidden" name="uploader_id" value="{{$uploader->id}}">
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="full_address" class="col-sm-3 control-label">Full Address</label>
                                            <div class="col-sm-4">
                                                <select name="sync[full_address]" class="form-control col-sm-9">
                                                    <option value="">Select Field</option>
                                                    @foreach($fields as $field)
                                                        <option value="{{$field['key']}}">{{$field['name']}} <{{$field['value']}}></option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-5">
                                                <label for="WithCityState"> <input type="checkbox" name="sync[WithCityState]"> Contains City, State Postal Code</label><br>
                                                <label for="AddressRanges"> <input type="checkbox" name="sync[AddressRanges]"> Contains Address Ranges</label>

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="unit" class="col-sm-3 control-label">City</label>
                                            <div class="col-sm-4">
                                                <select name="sync[city]" class="form-control col-sm-9">
                                                    <option value="">Select Field</option>
                                                    @foreach($fields as $field)
                                                        <option value="{{$field['key']}}">{{$field['name']}} <{{$field['value']}}></option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-5">
                                                <label for="StateInCity" class="control-label"> <input type="checkbox" name="sync[StateInCity]"> Contains State</label>
                                                <label for="PostalCodeInCity" class="control-label"> <input type="checkbox" name="sync[PostalCodeInCity]"> Contains PostalCode</label><br>
                                                <label> <input type="checkbox" onChange="$('#default-city').toggleClass('hidden')"> Has Default Value</label>
                                            </div>
                                        </div>
                                        <div class="form-group hidden" id="default-city">
                                            <label for="unit" class="col-sm-3 control-label">Default City</label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control col-sm-4" name="sync[default_city]">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="unit" class="col-sm-3 control-label">State</label>
                                            <div class="col-sm-4">
                                                <select name="sync[state]" class="form-control col-sm-9">
                                                    <option value="">Select Field</option>
                                                    @foreach($fields as $field)
                                                        <option value="{{$field['key']}}">{{$field['name']}} <{{$field['value']}}></option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-5">
                                                <label><input type="checkbox" onChange="$('#default-state').toggleClass('hidden')"> Has Default Value</label>
                                            </div>
                                        </div>
                                        <div class="form-group hidden" id="default-state">
                                            <label for="default-state" class="col-sm-3 control-label">Default State</label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control col-sm-4" name="sync[default_state]">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="unit" class="col-sm-3 control-label">Postal Code</label>
                                            <div class="col-sm-4">
                                                <select name="sync[postal_code]" class="form-control col-sm-9">
                                                    <option value="">Select Field</option>
                                                    @foreach($fields as $field)
                                                        <option value="{{$field['key']}}">{{$field['name']}} <{{$field['value']}}></option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-5">
                                                <label> <input type="checkbox" onChange="$('#default-postal-code').toggleClass('hidden')"> Has Default Value</label>
                                            </div>
                                        </div>
                                        <div class="form-group hidden" id="default-postal-code">
                                            <label for="default-state" class="col-sm-3 control-label">Default Postal Code</label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control col-sm-4" name="sync[default_state]">
                                            </div>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary waves-effect waves-light">Save Unparsed Address</button>
                                </form>
                            </div>

                            <div role="tabpanel" class="tab-pane" id="parsed">
                                <form action="{{action('UploaderController@post')}}" class="form-horizontal" method="post">
                                {!! csrf_field() !!}
                                <input type="hidden" name="slug" value="sync">
                                <input type="hidden" name="sync[class]" value="address">
                                <input type="hidden" name="sync[type]" value="parsed">
                                    <input type="hidden" name="uploader_id" value="{{$uploader->id}}">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="street_number" class="col-sm-3 control-label">Street Number</label>
                                        <div class="col-sm-4">
                                            <select name="sync[street_number]" class="form-control">
                                                <option value="">Select Field</option>
                                                @foreach($fields as $field)
                                                    <option value="{{$field['key']}}">{{$field['name']}} <{{$field['value']}}></option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-5">
                                            <label for="AddressRanges"> <input type="checkbox" name="sync[AddressRanges]"> Contains Address Ranges</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="street_name" class="col-sm-3 control-label">Street Name</label>
                                        <div class="col-sm-4">
                                            <select name="sync[street_name]" class="form-control">
                                                <option value="">Select Field</option>
                                                @foreach($fields as $field)
                                                    <option value="{{$field['key']}}">{{$field['name']}} <{{$field['value']}}></option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-5">
                                            <label for="TypeInStreetName" class="control-lable"> <input type="checkbox" name="sync[TypeInStreetName]"> Contains Street Type</label>
                                            <label for="TypeInStreetName"><input type="checkbox" name="sync[UnitInStreetName]"> Contains Apt/Unit</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="street_type" class="col-sm-3 control-label">Street Type</label>
                                        <div class="col-sm-4">
                                            <select name="sync[street_type]" class="form-control col-sm-9">
                                                <option value="">Select Field</option>
                                                @foreach($fields as $field)
                                                    <option value="{{$field['key']}}">{{$field['name']}} <{{$field['value']}}></option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="unit" class="col-sm-3 control-label">Unit</label>
                                        <div class="col-sm-4">
                                            <select name="sync[unit]" class="form-control col-sm-9">
                                                <option value="">Select Field</option>
                                                @foreach($fields as $field)
                                                    <option value="{{$field['key']}}">{{$field['name']}} <{{$field['value']}}></option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="unit" class="col-sm-3 control-label">City</label>
                                        <div class="col-sm-4">
                                            <select name="sync[city]" class="form-control col-sm-9">
                                                <option value="">Select Field</option>
                                                @foreach($fields as $field)
                                                    <option value="{{$field['key']}}">{{$field['name']}} <{{$field['value']}}></option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-5">
                                            <label for="StateInCity" class="control-label"><input type="checkbox" name="sync[StateInCity]"> Contains State</label>
                                            <label for="PostalCodeInCity" class="control-label"><input type="checkbox" name="sync[PostalCodeInCity]"> Contains PostalCode </label><br>
                                            <label><input type="checkbox" onChange="$('#default-parsed-city').toggleClass('hidden')"> Has Default Value</label>
                                        </div>
                                    </div>
                                    <div class="form-group hidden" id="default-parsed-city">
                                        <label for="unit" class="col-sm-3 control-label">Default City</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control col-sm-4" name="sync[default_city]">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="unit" class="col-sm-3 control-label">State</label>
                                        <div class="col-sm-4">
                                            <select name="sync[state]" class="form-control col-sm-9">
                                                <option value="">Select Field</option>
                                                @foreach($fields as $field)
                                                    <option value="{{$field['key']}}">{{$field['name']}} <{{$field['value']}}></option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-5">
                                            <label><input type="checkbox" onChange="$('#default-parsed-state').toggleClass('hidden')"> Has Default Value</label>
                                        </div>
                                    </div>
                                    <div class="form-group hidden" id="default-parsed-state">
                                        <label for="default-state" class="col-sm-3 control-label">Default State</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control col-sm-4" name="sync[default_state]">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="unit" class="col-sm-3 control-label">Postal Code</label>
                                        <div class="col-sm-4">
                                            <select name="sync[postal_code]" class="form-control col-sm-9">
                                                <option value="">Select Field</option>
                                                @foreach($fields as $field)
                                                    <option value="{{$field['key']}}">{{$field['name']}} <{{$field['value']}}></option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-5">
                                            <label> <input type="checkbox" onChange="$('#default-parsed-postal-code').toggleClass('hidden')"> Has Default Value</label>
                                        </div>
                                    </div>
                                    <div class="form-group hidden" id="default-parsed-postal-code">
                                        <label for="default-state" class="col-sm-3 control-label">Default Postal Code</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control col-sm-4" name="sync[default_postal_code]">
                                        </div>
                                    </div>
                                </div>
                                    <button class="btn btn-primary waves-effect waves-light">Save Parsed Address</button>
                                </form>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="property_id">
                                <form action="{{action('UploaderController@post')}}" class="form-horizontal" method="post">
                                    {!! csrf_field() !!}
                                    <input type="hidden" name="slug" value="sync">
                                    <input type="hidden" name="sync[class]" value="address">
                                    <input type="hidden" name="sync[type]" value="parsed">
                                    <input type="hidden" name="uploader_id" value="{{$uploader->id}}">
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="full_address" class="col-sm-3 control-label">CityNexus Property ID</label>
                                            <div class="col-sm-4">
                                                <select name="sync[property_id]" class="form-control col-sm-9">
                                                    <option value="">Select Field</option>
                                                    @foreach($fields as $field)
                                                        <option value="{{$field['key']}}">{{$field['name']}} <{{$field['value']}}></option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <button class="btn btn-primary waves-effect waves-light">Save CityNexus Property ID Address Sync</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>

@endsection