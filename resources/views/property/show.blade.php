@extends('master.main')

@section('title', title_case($property->address))

@section('main')
    <div class="page profile-page">
    <!-- page content -->
    <div class="pagecontent">

        <div class="col-sm-6 col-xs-12">
            <h1 class="font-thin h3 m-0">
                @if($property->is_unit)
                    <a href="{{route('properties.show', [$property->building_id])}}">{{title_case($property->building->OneLineAddress)}} </a> > Unit {{$property->unit}}
                @else
                    {{title_case($property->OneLineAddress)}}
                @endif

                @can('citynexus', ['properties', 'edit'])
                        <small>
                            <i role="button" class="fa fa-pencil" data-toggle="modal" data-target="#editProperty"></i>
                        </small>
                @endcan
            </h1>
            @include('snipits._tags', ['tags' => $property->tags, 'trashedTags' => $property->trashedTags])
        </div>
        <!-- row -->
        <div class="row">
            <div class="col-md-12 address-wrapper">
                <span class="address">

                </span>

            </div>
            <div class="col-md-4">

                <!-- boxs -->
                @if($property->units->count() > 0)
                    @include('property.snipits._units', ['units' => $property->units()->orderBy('unit')->get()])
                @endif
                <!-- /boxs -->

                @if($property->location != null)
                    <div class="panel panel-default">
                        <div id="pano" style="height: 250px"></div>
                    </div>
                    <div class="panel panel-default">
                        <div id="map" style="height: 250px"></div>
                    </div>
                @else
                    {{--<a href="{{route('property.geocode', [$property->id])}}">Geocode Property</a>--}}
                @endif

            </div>
            <div class="col-md-8">
                <!-- boxs -->
                <section class="boxs boxs-simple">
                    <!-- boxs body -->
                    <div class="boxs-body p-0">
                        <div role="tabpanel">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs tabs-dark-t" role="tablist">
                                <li role="presentation" @if(!isset($_GET['tab'])) class="active" @endif><a href="#datasets" aria-controls="datasets" role="tab" data-toggle="tab">Data Sets</a></li>
                                <li role="presentation" @if(isset($_GET['tab']) && $_GET['tab'] == 'comments') class="active" @endif><a href="#comments" aria-controls="comments" role="tab" data-toggle="tab">Comments</a></li>
                                @can('citynexus', ['files', 'view']) <li role="presentation" @if(isset($_GET['tab']) && $_GET['tab'] == 'files') class="active" @endif><a href="#files" aria-controls="files" role="tab" data-toggle="tab">Files</a></li> @endcan
                                <li role="presentation" @if(isset($_GET['tab']) && $_GET['tab'] == 'entities') class="active" @endif><a href="#entities" aria-controls="files" role="tab" data-toggle="tab">Entities</a></li>
                                <li role="presentation" @if(isset($_GET['tab']) && $_GET['tab'] == 'tasks') class="active" @endif><a href="#tasks" aria-controls="files" role="tab" data-toggle="tab">Tasks</a></li>
                                <li role="presentation" @if(isset($_GET['tab']) && $_GET['tab'] == 'actions') class="active" @endif><a href="#actions" aria-controls="actions" role="tab" data-toggle="tab">Actions</a></li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane @if(!isset($_GET['tab'])) active @endif" id="datasets">
                                    <div class="wrap-reset">
                                        @include('property.snipits._datasets')
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane @if(isset($_GET['tab']) && $_GET['tab'] == 'comments') active @endif" id="comments">
                                    <div class="wrap-reset">
                                        @include('snipits._comments', ['comments' => $property->comments, 'model' => 'App\\\PropertyMgr\\\Model\\\Property', 'model_id' => $property->id])
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane @if(isset($_GET['tab']) && $_GET['tab'] == 'files') active @endif" id="files">
                                    <div class="wrap-reset">
                                        @include('snipits._files', ['files' => $property->files, 'model_id' => $property->id, 'model_type' => 'App\\PropertyMgr\\Model\\Property'])
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane @if(isset($_GET['tab']) && $_GET['tab'] == 'entities') active @endif" id="entities">
                                    <div class="wrap-reset">
                                        @include('property.snipits._entities')
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane @if(isset($_GET['tab']) && $_GET['tab'] == 'tasks') active @endif" id="tasks">
                                    <div class="wrap-reset">
                                        @include('snipits._tasks', ['lists' => $property->tasks, 'model_type' => 'App\\\PropertyMgr\\\Model\\\Property', 'model_id' => $property->id])
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane @if(isset($_GET['tab']) && $_GET['tab'] == 'actions') active @endif" id="actions">
                                    <div class="wrap-reset">
                                        @include('property.snipits._actions')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /boxs body -->
                </section>
                <!-- /boxs -->
            </div>
            <!-- /col -->
        </div>
        <!-- /row -->
    </div>
    <!-- /page content -->
    </div>
@endsection

@push('style')

<link rel="stylesheet" href="/assets/js/vendor/datetimepicker/css/bootstrap-datetimepicker.min.css">
<link rel="stylesheet" href="/assets/js/vendor/chosen/chosen.css">

<style>
    .address {
        font-size: 18px;
        font-weight: 300;
    }
    .address-wrapper {
        padding-bottom: 15px;
    }
</style>

@endpush

@push('modal')

<div class="modal fade" id="editProperty">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Edit Property</h4>
			</div>
            <form class="form-horizontal" action="{{route('properties.update', [$property->id])}}" method="post">
			    {{csrf_field()}}
                {{method_field("patch")}}
                <div class="modal-body">
                    <div class="col-sm-12">
                        <div class="alert alert-info">
                            Please be cautious when editing a property address from this view as typos and othere errors
                            will not be corrected before being saved to the system. This may effect CityNexus's ability
                            to automatically match with this address in the future.
                        </div>
                    </div>
                <input type="hidden" name="address" id="new_address" value="{{$property->address}}">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="house_number" class="col-sm-3 control-label">House Number</label>
                            <div class="col-sm-9">
                                <input type="text" id="house_number" class="form-control address-part" value=" ">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="street_name" class="col-sm-3 control-label">Street Name</label>
                            <div class="col-sm-9">
                                <input type="text" id="street_name" class="form-control address-part" value=" ">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="street_name" class="col-sm-3 control-label">Street Type</label>
                            <div class="col-sm-9">
                                <select type="text" id="street_type" class="form-control address-part">
                                    <option value="AV">Avenue</option>
                                    <option value="BL">Boulevard</option>
                                    <option value="CR">Circle</option>
                                    <option value="CT">Court</option>
                                    <option value="DR">Drive</option>
                                    <option value="EX">Expressway</option>
                                    <option value="HWY">Highway</option>
                                    <option value="LN">Lane</option>
                                    <option value="PL">Place</option>
                                    <option value="PZ">Plaza</option>
                                    <option value="RD">Road</option>
                                    <option value="ST" selected>Street</option>
                                    <option value="TR">Terrace</option>
                                    <option value="WY">Way</option>
                                    <option value="">[None]</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="full_address_preview" class="col-sm-3 control-label">Full Address</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control"  id="full_address_preview" value="{{$property->address}}" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="unit" class="col-sm-3 control-label">Unit</label>
                            <div class="col-sm-9">
                                <input type="text" id="unit" class="form-control" value="">
                                <p class="help-block mb-0">Enter just the unit value. e.g. "4" or "6 Rear" rather than "#4" or "Unit 6 Rear".</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="city" class="col-sm-3 control-label">City</label>
                            <div class="col-sm-9">
                                <input type="text" name="city" id="city" class="form-control" value="{{$property->city}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="state" class="col-sm-3 control-label">State</label>
                            <div class="col-sm-9">
                                <select name="state" id="state" class="form-control">
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
                                    <option value="MA" selected>Massachusetts</option>
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
                            <label for="city" class="col-sm-3 control-label">Postal Code</label>
                            <div class="col-sm-9">
                                <input type="text" name="city" id="city" class="form-control" value="{{$property->postcode}}">
                            </div>
                        </div>
                    </div>
                </div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-raised" data-dismiss="modal">Close</button>
				<input type="submit" class="btn btn-primary btn-raised" value="Save changes">
			</div>
            </form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endpush

@push('scripts')

<script src="/assets/js/vendor/chosen/chosen.jquery.min.js"></script>
<script src="/assets/js/vendor/filestyle/bootstrap-filestyle.min.js"></script>
<script>
    $('#state').val({{$property->state}});
</script>
@if($property->location != null)
    <script>

        function initialize() {
            var point = {lat: {{$property->location->getLat()}}, lng:{{$property->location->getLng()}} };
            var map = new google.maps.Map(document.getElementById('map'), {
                center: point,
                zoom: 16
            });
            var panorama = new google.maps.StreetViewPanorama(
                    document.getElementById('pano'), {
                        position: point,
                    });
            map.setStreetView(panorama);
        }

        $(".address-part").change(function(){
            var address = '';
            address += $('#house_number').val().trim();
            address = address.trim() + ' ' + $('#street_name').val().trim();
            address = address.trim() + ' ' + $('#street_type').val();

            $('#full_address_preview').val(address.toUpperCase());
            $('#new_address').val(address.trim());
        });

    </script>
@endif

@if(env('GMAPI_KEY') != null)
    <script async defer src="{{'https://maps.googleapis.com/maps/api/js?key=' . env('GMAPI_KEY') . '&signed_in=true&callback=initialize'}}">
    </script>
@endif

@endpush