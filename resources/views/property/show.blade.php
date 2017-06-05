@extends('master.main')

@section('title', title_case($property->address))

@section('main')
    <div class="page profile-page">
    <!-- page content -->
    <div class="pagecontent">

        <!-- row -->
        <div class="row">
            <div class="col-md-12 address-wrapper">
                <span class="address">
                    @if($property->is_unit)
                        <a href="{{route('properties.show', [$property->building_id])}}">{{title_case($property->building->OneLineAddress)}} </a> > Unit {{$property->unit}}
                    @else
                    {{title_case($property->OneLineAddress)}}
                    @endif
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
                    <a href="{{route('property.geocode', [$property->id])}}">Geocode Property</a>
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
                                <li role="presentation" @if(isset($_GET['tab']) && $_GET['tab'] == 'files') class="active" @endif><a href="#files" aria-controls="files" role="tab" data-toggle="tab">Files</a></li>
                                <li role="presentation"><a href="#actions" aria-controls="actions" role="tab" data-toggle="tab">Actions</a></li>
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
                                        @include('property.snipits._comments')
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane @if(isset($_GET['tab']) && $_GET['tab'] == 'files') active @endif" id="files">
                                    <div class="wrap-reset">
                                        @include('property.snipits._files')
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

@push('scripts')

<script src="/assets/js/vendor/chosen/chosen.jquery.min.js"></script>
<script src="/assets/js/vendor/filestyle/bootstrap-filestyle.min.js"></script>
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

    </script>
@endif

@if(env('GMAPI_KEY') != null)
    <script async defer src="{{'https://maps.googleapis.com/maps/api/js?key=' . env('GMAPI_KEY') . '&signed_in=true&callback=initialize'}}">
    </script>
@endif

@endpush