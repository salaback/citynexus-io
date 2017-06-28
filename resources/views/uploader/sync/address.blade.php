@extends('master.main')

@section('title', 'Address Sync')

@section('main')


    <div class="col-lg-offset-1 col-lg-10 ">
        <section class="boxs">
            <div class="boxs-header dvd dvd-btm">
                <h1 class="custom-font"><strong>Create</strong> address sync</h1>

            </div>
            <div class="boxs-body">
                <div class="bs-example bs-example-tabs" data-example-id="togglable-tabs">
                    <ul class="nav nav-tabs" id="myTabs" role="tablist">
                        <li role="presentation" class="active"><a href="#unparsed" id="unparsed-tab" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true">Unparsed Address</a></li>
                        <li role="presentation" class=""><a href="#parsed" role="tab" id="parsed-tab" data-toggle="tab" aria-controls="profile" aria-expanded="false">Parsed Address</a></li>
                        <li role="presentation" class=""><a href="#property_id" role="tab" id="property_id-tab" data-toggle="tab" aria-controls="profile" aria-expanded="false">Property ID</a></li>

                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade active in" role="tabpanel" id="unparsed" aria-labelledby="home-tab">
                            <form action="{{route('uploader.storeSync')}}" class="form-horizontal" method="post">
                                {!! csrf_field() !!}
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
                                                    <option value="{{$field['key']}}">{{$field['name']}}</option>
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
                                                    <option value="{{$field['key']}}">{{$field['name']}}</option>
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
                                                    <option value="{{$field['key']}}">{{$field['name']}}</option>
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
                                            <select class="form-control col-sm-4" name="sync[default_state]">
                                                <option value="AL">Alabama</option>
                                                <option value="AK">Alaska</option>
                                                <option value="AZ">Arizona</option>
                                                <option value="AR">Arkansas</option>
                                                <option value="CA">California</option>
                                                <option value="CO">Colorado</option>
                                                <option value="CT">Connecticut</option>
                                                <option value="DE">Delaware</option>
                                                <option value="DC">District Of Columbia</option>
                                                <option value="FL">Florida</option>
                                                <option value="GA">Georgia</option>
                                                <option value="HI">Hawaii</option>
                                                <option value="ID">Idaho</option>
                                                <option value="IL">Illinois</option>
                                                <option value="IN">Indiana</option>
                                                <option value="IA">Iowa</option>
                                                <option value="KS">Kansas</option>
                                                <option value="KY">Kentucky</option>
                                                <option value="LA">Louisiana</option>
                                                <option value="ME">Maine</option>
                                                <option value="MD">Maryland</option>
                                                <option value="MA">Massachusetts</option>
                                                <option value="MI">Michigan</option>
                                                <option value="MN">Minnesota</option>
                                                <option value="MS">Mississippi</option>
                                                <option value="MO">Missouri</option>
                                                <option value="MT">Montana</option>
                                                <option value="NE">Nebraska</option>
                                                <option value="NV">Nevada</option>
                                                <option value="NH">New Hampshire</option>
                                                <option value="NJ">New Jersey</option>
                                                <option value="NM">New Mexico</option>
                                                <option value="NY">New York</option>
                                                <option value="NC">North Carolina</option>
                                                <option value="ND">North Dakota</option>
                                                <option value="OH">Ohio</option>
                                                <option value="OK">Oklahoma</option>
                                                <option value="OR">Oregon</option>
                                                <option value="PA">Pennsylvania</option>
                                                <option value="RI">Rhode Island</option>
                                                <option value="SC">South Carolina</option>
                                                <option value="SD">South Dakota</option>
                                                <option value="TN">Tennessee</option>
                                                <option value="TX">Texas</option>
                                                <option value="UT">Utah</option>
                                                <option value="VT">Vermont</option>
                                                <option value="VA">Virginia</option>
                                                <option value="WA">Washington</option>
                                                <option value="WV">West Virginia</option>
                                                <option value="WI">Wisconsin</option>
                                                <option value="WY">Wyoming</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="unit" class="col-sm-3 control-label">Postal Code</label>
                                        <div class="col-sm-4">
                                            <select name="sync[postcode]" class="form-control col-sm-9">
                                                <option value="">Select Field</option>
                                                @foreach($fields as $field)
                                                    <option value="{{$field['key']}}">{{$field['name']}}</option>
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
                                            <input type="text" class="form-control col-sm-4" name="sync[default_postcode]">
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary btn-raised">Save Unparsed Address</button>
                            </form>
                        </div>
                        <div class="tab-pane fade" role="tabpanel" id="parsed" aria-labelledby="profile-tab">
                            <form action="{{route('uploader.storeSync')}}" class="form-horizontal" method="post">
                                {!! csrf_field() !!}
                                <input type="hidden" name="sync[class]" value="address">
                                <input type="hidden" name="sync[type]" value="parsed">
                                <input type="hidden" name="uploader_id" value="{{$uploader->id}}">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="house_number" class="col-sm-3 control-label">House Number</label>
                                        <div class="col-sm-4">
                                            <select name="sync[house_number]" class="form-control">
                                                <option value="">Select Field</option>
                                                @foreach($fields as $field)
                                                    <option value="{{$field['key']}}">{{$field['name']}} </option>
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
                                                    <option value="{{$field['key']}}">{{$field['name']}} </option>
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
                                                    <option value="{{$field['key']}}">{{$field['name']}} </option>
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
                                                    <option value="{{$field['key']}}">{{$field['name']}} </option>
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
                                                    <option value="{{$field['key']}}">{{$field['name']}} </option>
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
                                                    <option value="{{$field['key']}}">{{$field['name']}} </option>
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
                                            <select class="form-control col-sm-4" name="sync[default_state]">
                                                <option value="AL">Alabama</option>
                                                <option value="AK">Alaska</option>
                                                <option value="AZ">Arizona</option>
                                                <option value="AR">Arkansas</option>
                                                <option value="CA">California</option>
                                                <option value="CO">Colorado</option>
                                                <option value="CT">Connecticut</option>
                                                <option value="DE">Delaware</option>
                                                <option value="DC">District Of Columbia</option>
                                                <option value="FL">Florida</option>
                                                <option value="GA">Georgia</option>
                                                <option value="HI">Hawaii</option>
                                                <option value="ID">Idaho</option>
                                                <option value="IL">Illinois</option>
                                                <option value="IN">Indiana</option>
                                                <option value="IA">Iowa</option>
                                                <option value="KS">Kansas</option>
                                                <option value="KY">Kentucky</option>
                                                <option value="LA">Louisiana</option>
                                                <option value="ME">Maine</option>
                                                <option value="MD">Maryland</option>
                                                <option value="MA">Massachusetts</option>
                                                <option value="MI">Michigan</option>
                                                <option value="MN">Minnesota</option>
                                                <option value="MS">Mississippi</option>
                                                <option value="MO">Missouri</option>
                                                <option value="MT">Montana</option>
                                                <option value="NE">Nebraska</option>
                                                <option value="NV">Nevada</option>
                                                <option value="NH">New Hampshire</option>
                                                <option value="NJ">New Jersey</option>
                                                <option value="NM">New Mexico</option>
                                                <option value="NY">New York</option>
                                                <option value="NC">North Carolina</option>
                                                <option value="ND">North Dakota</option>
                                                <option value="OH">Ohio</option>
                                                <option value="OK">Oklahoma</option>
                                                <option value="OR">Oregon</option>
                                                <option value="PA">Pennsylvania</option>
                                                <option value="RI">Rhode Island</option>
                                                <option value="SC">South Carolina</option>
                                                <option value="SD">South Dakota</option>
                                                <option value="TN">Tennessee</option>
                                                <option value="TX">Texas</option>
                                                <option value="UT">Utah</option>
                                                <option value="VT">Vermont</option>
                                                <option value="VA">Virginia</option>
                                                <option value="WA">Washington</option>
                                                <option value="WV">West Virginia</option>
                                                <option value="WI">Wisconsin</option>
                                                <option value="WY">Wyoming</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="unit" class="col-sm-3 control-label">Postal Code</label>
                                        <div class="col-sm-4">
                                            <select name="sync[postcode]" class="form-control col-sm-9">
                                                <option value="">Select Field</option>
                                                @foreach($fields as $field)
                                                    <option value="{{$field['key']}}">{{$field['name']}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-5">
                                            <label> <input type="checkbox" onChange="$('#default-parsed-postal-code').toggleClass('hidden')"> Has Default Value</label>
                                        </div>
                                    </div>
                                    <div class="form-group hidden" id="default-parsed-postal-code">
                                        <label for="default-postal-code" class="col-sm-3 control-label">Default Postal Code</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control col-sm-4" name="sync[default_postcode]">
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary btn-raised">Save Parsed Address</button>
                            </form>
                        </div>
                        <div class="tab-pane fade" role="tabpanel" id="property_id" aria-labelledby="profile-tab">
                            <form action="{{route('uploader.storeSync')}}" class="form-horizontal" method="post">
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
                                                    <option value="{{$field['key']}}">{{$field['name']}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary btn-raised">Save CityNexus Property ID Address Sync</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection