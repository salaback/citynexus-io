@extends('master.main')

@section('title', 'Address Sync')

@section('main')

    <div class="col-lg-offset-1 col-lg-10 ">
        <section class="boxs">
            <div class="boxs-header dvd dvd-btm">
                <h1 class="custom-font"><strong>Create</strong> entity sync</h1>

            </div>
            <div class="boxs-body">
                <div class="row">
                    <div class="col-sm-5 form-horizontal">
                        <div class="panel-group" id="entities" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="unparsedEntityHeading">
                                    <h4 class="panel-title"> <a data-toggle="collapse" data-parent="#entities" href="#unparsedEntity" aria-expanded="true" aria-controls="unparsedEntity" onclick="entity = 'unparsed';">Unparsed Entity Name</a> </h4>
                                </div>
                                <div id="unparsedEntity" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="unparsedEntityHeading">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="full_address" class="col-sm-4 control-label">Full Entity
                                                    Name</label>
                                                <div class="col-sm-7">
                                                    <select name="sync[full_name]" class="form-control col-sm-9" id="full_name">
                                                        <option value="">Select Field</option>
                                                        @foreach($fields as $field)
                                                            <option value="{{$field['key']}}">{{$field['name']}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="full_address" class="col-sm-4 control-label" id="format">Typical Name
                                                    Format</label>
                                                <div class="col-sm-7">
                                                    <select type="text" name="sync[format]" id='format' class="form-control">
                                                        <option value="">Select One</option>
                                                        <option value="FirstMLast">First M Last</option>
                                                        <option value="LastFirstM">Last First M</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="full_address" class="col-sm-4 control-label" >Entity Role</label>
                                                <div class="col-sm-7">
                                                    <select type="text" name="sync[role]" id="unparsed_role" class="form-control">
                                                        <option value="">Select One</option>
                                                        <option value="attorney">Attorney</option>
                                                        <option value="common">Common Name</option>
                                                        <option value="manger">Property Manager</option>
                                                        <option value="Receiver">Receiver</option>
                                                        <option value="tenant">Tenant</option>
                                                        <option value="owner">Owner</option>
                                                        <option value="other">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="parsedEntityHeading">
                                    <h4 class="panel-title"> <a class="collapsed" data-toggle="collapse" data-parent="#entities" href="#parsedEntity" aria-expanded="false" aria-controls="parsedEntity" onclick="entity = 'parsed';"> Parsed Entity Name</a> </h4>
                                </div>
                                <div id="parsedEntity" class="panel-collapse collapse" role="tabpanel" aria-labelledby="parsedEntityHeading">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="full_address" class="col-sm-4 control-label">Title</label>
                                                <div class="col-sm-8">
                                                    <select name="sync[entity][title]" class="form-control col-sm-9" id="title">
                                                        <option value=""></option>
                                                        @foreach($fields as $field)
                                                            <option value="{{$field['key']}}">{{$field['name']}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="first_name" class="col-sm-4 control-label">First
                                                    Name</label>
                                                <div class="col-sm-8">
                                                    <select name="sync[entity][first_name]"
                                                            class="form-control col-sm-9">
                                                        <option value=""></option>
                                                        @foreach($fields as $field)
                                                            <option value="{{$field['key']}}">{{$field['name']}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="middle_name" class="col-sm-4 control-label">Middle
                                                    Name</label>
                                                <div class="col-sm-8">
                                                    <select name="sync[entity][middle_name]"
                                                            class="form-control col-sm-9"
                                                            id="middle_name">
                                                        <option value=""></option>
                                                        @foreach($fields as $field)
                                                            <option value="{{$field['key']}}">{{$field['name']}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="last_name" class="col-sm-4 control-label">Last Name</label>
                                                <div class="col-sm-8">
                                                    <select name="sync[entity][last_name]"
                                                            class="form-control col-sm-9"
                                                            id="last_name">
                                                        <option value=""></option>
                                                        @foreach($fields as $field)
                                                            <option value="{{$field['key']}}">{{$field['name']}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="suffix" class="col-sm-4 control-label">Suffix</label>
                                                <div class="col-sm-8">
                                                    <select name="sync[entity][suffix]" class="form-control col-sm-9" id="suffix">
                                                        <option value=""></option>
                                                        @foreach($fields as $field)
                                                            <option value="{{$field['key']}}">{{$field['name']}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group">
                                                    <label for="full_address" class="col-sm-4 control-label">Entity
                                                        Role</label>
                                                    <div class="col-sm-8">
                                                        <select type="text" name="sync[role]" id='parsed_role'
                                                                class="form-control">
                                                            <option value="">Select One</option>
                                                            <option value="owner">Owner</option>
                                                            <option value="tenant">Tenant</option>
                                                            <option value="manger">Property Manager</option>
                                                            <option value="Receiver">Receiver</option>
                                                            <option value="common">Common Name</option>
                                                            <option value="other">Other</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-7 form-horizontal">
                        <div class="togglebutton">
                            <label>
                                <input type="checkbox" onclick="$('#addressSync').toggleClass('hidden')" id="hasAddress">
                                Attach a mailing address
                            </label>
                        </div>
                        <br>
                        <div id="addressSync" class="hidden">
                            <label>
                                <input type="checkbox" id="makePrimary">
                                Make this the primary mailing address for entity.
                            </label>
                            <div class="panel-group" id="addresses" role="tablist" aria-multiselectable="true">
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="unparsedAddressHeading">
                                        <h4 class="panel-title"> <a class="collapsed" data-toggle="collapse" data-parent="#addresses" href="#unparsedAddress" aria-expanded="true" aria-controls="unparsedAddress" onclick="address = 'unparsed';"> Unparsed Mailing Address</a> </h4>
                                    </div>
                                    <div id="unparsedAddress" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="unparsedAddressHeading">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="form-group">
                                                    <label for="full_address" class="col-sm-3 control-label">Full Address</label>
                                                    <div class="col-sm-4">
                                                        <select name="sync[full_address]" class="form-control col-sm-9" id="full_address">
                                                            <option value="">Select Field</option>
                                                            @foreach($fields as $field)
                                                                <option value="{{$field['key']}}">{{$field['name']}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-5">
                                                        <label for="WithCityState"> <input type="checkbox" name="sync[WithCityState]" id="uWithCityState"> Contains City, State Postal Code</label><br>


                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="unit" class="col-sm-3 control-label">City</label>
                                                    <div class="col-sm-4">
                                                        <select name="sync[city]" class="form-control col-sm-9" id="uCity">
                                                            <option value="">Select Field</option>
                                                            @foreach($fields as $field)
                                                                <option value="{{$field['key']}}">{{$field['name']}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-5">
                                                        <label for="StateInCity" class="control-label"> <input type="checkbox" name="sync[StateInCity]" id="uStateInCity"> Contains State</label>
                                                        <label for="PostalCodeInCity" class="control-label"> <input type="checkbox" name="sync[PostalCodeInCity]" id="uPostalCodeInCity"> Contains PostalCode</label><br>
                                                        <label> <input type="checkbox" onChange="$('#default-city').toggleClass('hidden')"> Has Default Value</label>
                                                    </div>
                                                </div>
                                                <div class="form-group hidden" id="default-city">
                                                    <label for="unit" class="col-sm-3 control-label">Default City</label>
                                                    <div class="col-sm-4">
                                                        <input type="text" class="form-control col-sm-4" name="sync[default_city]" id="uDefaultCity">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="unit" class="col-sm-3 control-label">State</label>
                                                    <div class="col-sm-4">
                                                        <select name="sync[state]" class="form-control col-sm-9" id="uState">
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
                                                        <select class="form-control col-sm-4" name="sync[default_state]" id="uDefaultState">
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
                                                        <select name="sync[postcode]" class="form-control col-sm-9" id="uPostcode">
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
                                                        <input type="text" class="form-control col-sm-4" name="sync[default_postcode]" id="uDefaultPostcode">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="parsedAddressHeading">
                                        <h4 class="panel-title"> <a data-toggle="collapse" data-parent="#addresses" href="#parsedAddress" aria-expanded="false" aria-controls="parsedAddress" onclick="address = 'parsed';">Parsed Mailing Address</a> </h4>
                                    </div>
                                    <div id="parsedAddress" class="panel-collapse collapse" role="tabpanel" aria-labelledby="parsedAddressHeading">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="form-group">
                                                    <label for="house_number" class="col-sm-3 control-label">House Number</label>
                                                    <div class="col-sm-4">
                                                        <select name="sync[house_number]" class="form-control" id="houseNumber">
                                                            <option value="">Select Field</option>
                                                            @foreach($fields as $field)
                                                                <option value="{{$field['key']}}">{{$field['name']}} </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="street_name" class="col-sm-3 control-label">Street Name</label>
                                                    <div class="col-sm-4">
                                                        <select name="sync[street_name]" class="form-control" id="streetName">
                                                            <option value="">Select Field</option>
                                                            @foreach($fields as $field)
                                                                <option value="{{$field['key']}}">{{$field['name']}} </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-5">
                                                        <label for="TypeInStreetName" class="control-lable"> <input type="checkbox" name="sync[TypeInStreetName]" id="pTypeInStreetName"> Contains Street Type</label>
                                                        <label for="TypeInStreetName"><input type="checkbox" name="sync[UnitInStreetName]" id="pUnitInStreetName"> Contains Apt/Unit</label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="street_type" class="col-sm-3 control-label">Street Type</label>
                                                    <div class="col-sm-4">
                                                        <select name="sync[street_type]" id="streetType" class="form-control col-sm-9">
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
                                                    <label for="unit" class="col-sm-3 control-label" id="pCity">City</label>
                                                    <div class="col-sm-4">
                                                        <select name="sync[city]" class="form-control col-sm-9">
                                                            <option value="">Select Field</option>
                                                            @foreach($fields as $field)
                                                                <option value="{{$field['key']}}">{{$field['name']}} </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-5">
                                                        <label for="StateInCity" class="control-label"><input type="checkbox" name="sync[StateInCity]" id="pStateinCity"> Contains State</label>
                                                        <label for="PostalCodeInCity" class="control-label"><input type="checkbox" name="sync[PostalCodeInCity]" id="pPostCodeInCity"> Contains PostalCode </label><br>
                                                        <label><input type="checkbox" onChange="$('#default-parsed-city').toggleClass('hidden')"> Has Default Value</label>
                                                    </div>
                                                </div>
                                                <div class="form-group hidden" id="default-parsed-city">
                                                    <label for="unit" class="col-sm-3 control-label">Default City</label>
                                                    <div class="col-sm-4">
                                                        <input type="text" class="form-control col-sm-4" name="sync[default_city]" id="pDefaultCity">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="unit" class="col-sm-3 control-label">State</label>
                                                    <div class="col-sm-4">
                                                        <select name="sync[state]" class="form-control col-sm-9" id="pState">
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
                                                        <select class="form-control col-sm-4" name="sync[default_state]" id="pDefaultState">
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
                                                        <select name="sync[postcode]" class="form-control col-sm-9" id="pPostCode">
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
                                                    <label for="default-postal-code" class="col-sm-3 control-label" id="pDefaultPostCode">Default Postal Code</label>
                                                    <div class="col-sm-4">
                                                        <input type="text" class="form-control col-sm-4" name="sync[default_postcode]">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="boxs-footer">
                <button onclick="submitForm()" class="btn btn-primary btn-raised" id="submitForm">Save Entity Sync</button>
            </div>
        </section>
    </div>

@endsection

@push('scripts')
<script>

    var entity = 'unparsed';
    var address = 'unparsed';
    var addressData;
    var entityData;

    function submitForm()
    {
        $('#submitForm').html('<i class="fa fa-spin fa-spinner"></i> Saving');
        if(!document.getElementById('hasAddress').checked)
        {
            addressData = false;
        }
        else if (address == 'unparsed')
        {
            addressData = {
                type: 'unparsed',
                full_address: $('#full_address').val(),
                city: $('#uCity').val(),
                default_city: $('#uDefaultCity').val(),
                state: $('#uState').val(),
                default_state: $('#uDefaultState').val(),
                postcode: $('#uPostcode').val(),
                default_postcode: $('#uDefaultPostcode').val(),
            };

            if(document.getElementById('uWithCityState').checked) addressData.push('WithCityState', true);
            if(document.getElementById('uStateInCity').checked) addressData.push('StateinCity', true);
            if(document.getElementById('uPostalCodeInCity').checked) addressData.push('PostalCodeInCity', true);

        }
        else if (address == 'parsed')
        {
            addressData = {
                type: 'parsed',
                house_number: $('#houseNumber').val(),
                street_name: $('#streetName').val(),
                street_type: $('#streetType').val(),
                unit: $('#unit').val(),
                city: $('#pCity').val(),
                default_city: $('#pDefaultCity').val(),
                state: $('#pState').val(),
                default_state: $('#pDefaultState').val(),
                postcode: $('#pPostcode').val(),
                default_postcode: $('#pDefaultPostCode').val(),
            };

            if(document.getElementById('pTypeInStreetName').checked) addressData.push('TypeInStreetName', true);
            if(document.getElementById('pUnitInStreetName').checked) addressData.push('UnitInStreetName', true);
            if(document.getElementById('pStateInCity').checked) addressData.push('StateInCity', true);
            if(document.getElementById('pPostCodeInCity').checked) addressData.push('PostCodeInCity', true);
        }


        if(entity == 'unparsed')
        {
            entityData = {
                class: 'entity',
                type: 'unparsed',
                address: addressData,
                full_name: $('#full_name').val(),
                format: $('#format').val(),
                role: $('#unparsed_role').val(),
            }
        }
        else if (entity == 'parsed')
        {
            entityData = {
                class: 'entity',
                type: 'parsed',
                address: addressData,
                title: $('#title').val(),
                first_name: $('#first_name').val(),
                middle_name: $('#middle_name').val(),
                last_name: $('#last_name').val(),
                suffix: $('#suffix').val(),
                role: $('#parsed_role').val(),
            }
        }

        $.ajax({
            url: "{{route('uploader.storeSync')}}",
            method: "POST",
            data: {
                _token: "{{csrf_token()}}",
                uploader_id: {{$uploader->id}},
                sync: entityData
            },
            success: function() {
                window.location = '{{route('uploader.show', [$uploader->id])}}';
            },
            error: function(){
                $('#submitForm').html('Save Entity');

                alert('Uh oh! Something didn\'t work right!');
            }
        })
    }
</script>
@endpush