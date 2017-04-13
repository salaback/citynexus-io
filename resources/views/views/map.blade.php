<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>CityNexus | Mapping Portal</title>
    <link rel="icon" type="image/ico" href="assets/images/favicon.ico" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/assets/css/vendor/animsition.min.css">
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/citynexus.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.2/dist/leaflet.css" />
    <style>
        #content #mapid {
            margin: 0px;
            height: 100%;
            width: 100%;
        }
        .page {
            padding: 0px !important;
        }
    </style>

</head>
<body id="oakleaf" class="main_Wrapper leftmenu-offcanvas device-lg theme-default default-theme-color header-fixed aside-fixed rightmenu-show leftmenu-sm">

<!--  Application Content -->
<div id="wrap" class="animsition">

    <!--  HEADER Content  -->
    <section id="header">
        <header class="clearfix">
            <!-- Branding -->
            <div class="branding"> <a class="" href="/"><img class="logo" src="/img/logo_on_black.gif" alt=""></a> <a role="button" tabindex="0" class="offcanvas-toggle visible-xs-inline"><i class="fa fa-bars"></i></a> </div>
            <!-- Branding end -->

            <!-- Left-side navigation -->
            <ul class="nav-left pull-left list-unstyled list-inline">
                <li class="leftmenu-collapse"><a role="button" tabindex="0" class="collapse-leftmenu"><i class="fa fa-arrow-circle-o-left"></i></a></li>
            </ul>
            <!-- Left-side navigation end -->

            <!-- Search -->
            <div class="search" id="main-search">
                <input type="text" class="form-control underline-input" placeholder="Seach by property...">
            </div>
            <!-- Search end -->

            <!-- Right-side navigation -->
            <ul class="nav-right pull-right list-inline">
                <li class="dropdown notifications"> <a href class="dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-bell"></i>
                        @if(\Illuminate\Support\Facades\Auth::user()->unreadNotifications->count() > 0)<div class="notify"><span class="heartbit"></span><span class="point"></span></div>@endif
                    </a>
                    <div class="dropdown-menu pull-right with-arrow panel panel-default ">
                        <ul class="list-group">
                            @forelse(\Illuminate\Support\Facades\Auth::user()->notifications as $notification)
                                @if($notification->type == 'App\Notifications\DataProcessed')
                                    @include('master.notifications.dataProcessed')
                                @endif
                            @empty
                            @endforelse
                        </ul>
                        <div class="panel-footer"> <a role="button" tabindex="0">Show all notifications <i class="fa fa-angle-right pull-right"></i></a> </div>
                    </div>
                </li>
                <li class="toggle-right-leftmenu"><a role="button" tabindex="0"><i class="fa fa-gear"></i></a></li>
                <li class="toggle-right-leftmenu"><a role="button" tabindex="0"><i class="fa fa-sign-out"></i></a></li>
            </ul>
            <!-- Right-side navigation end -->
        </header>
    </section>
    <!--/ HEADER Content  -->

    <!--  CONTROLS Content  -->
    <div id="controls">
        <!--SIDEBAR Content -->
        <aside id="leftmenu">
            <div id="leftmenu-wrap">
                <div class="panel-group slim-scroll" role="tablist">
                    <div class="panel panel-default">
                        <div id="leftmenuNav" class="panel-collapse collapse in" role="tabpanel">
                            <div class="panel-body">
                                <!--  NAVIGATION Content -->
                                @include('master.snipits.left_nav')
                            </div>
                        </div>
                    </div>
                    <div class="panel settings panel-default">
                        <div class="panel-heading" role="tab">
                            <h4 class="panel-title"><a data-toggle="collapse" href="#leftmenuControls">General Settings <i class="fa fa-angle-up"></i></a></h4>
                        </div>
                        <div id="leftmenuControls" class="panel-collapse collapse in" role="tabpanel">
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-xs-8">Switch ON</label>
                                        <div class="col-xs-4 control-label">
                                            <div class="togglebutton">
                                                <label>
                                                    <input type="checkbox" checked="">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-xs-8">Switch OFF</label>
                                        <div class="col-xs-4 control-label">
                                            <div class="togglebutton">
                                                <label>
                                                    <input type="checkbox" >
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="milestone-sidbar">
                            <div class="text-center-folded"> <span class="pull-right pull-none-folded">60%</span> <span class="hidden-folded">Milestone</span> </div>
                            <div class="progress progress-xxs m-t-sm dk">
                                <div class="progress-bar progress-bar-info" style="width: 60%;"> </div>
                            </div>
                            <div class="text-center-folded"> <span class="pull-right pull-none-folded">35%</span> <span class="hidden-folded">Release</span> </div>
                            <div class="progress progress-xxs m-t-sm dk">
                                <div class="progress-bar progress-bar-primary" style="width: 35%;"> </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
        <!--/ SIDEBAR Content -->

        <!--RIGHTBAR Content -->
        <aside id="rightmenu">
            <div role="tabpanel">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#datasets" aria-controls="datasets" role="tab" data-toggle="tab">Data Sets</a></li>
                    {{--<li role="presentation"><a href="#datasets" aria-controls="computed" role="tab" data-toggle="tab">Computed</a></li>--}}
                    {{--<li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Settings</a></li>--}}
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">

                    <div role="tabpanel" class="tab-pane active" id="datasets">
                        <div class="boxs-body">
                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                @foreach($datasets as $dataset)
                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="dataset_{{$dataset->id}}_heading">
                                            <h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#dataset_{{$dataset->id}}" aria-expanded="false" aria-controls="collapseOne" class="collapsed">{{$dataset->name}}</a> </h4>
                                        </div>
                                        <div id="dataset_{{$dataset->id}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne" aria-expanded="false" style="height: 0px;">
                                            <div class="panel-body">
                                                @foreach($dataset->schema as $item)
                                                    @if(isset($item['show']) && $item['show'] == 'on')
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" name="optionsCheckboxes" onclick="loadDatasetPoint({{$dataset->id}}, '{{$item['key']}}')"><span class="checkbox-material"></span>
                                                            {{$item['name']}}</label>
                                                    </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="computed">

                    </div>
                    <div role="tabpanel" class="tab-pane" id="settings">
                        <h6>Chat Settings</h6>
                        <ul class="settings">
                            <li>
                                <div class="form-group">
                                    <label class="col-xs-8 control-label">Show Offline Users</label>
                                    <div class="col-xs-4 control-label text-right">
                                        <div class="togglebutton">
                                            <label>
                                                <input type="checkbox" checked="">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="form-group">
                                    <label class="col-xs-8 control-label">Show Fullname</label>
                                    <div class="col-xs-4 control-label text-right">
                                        <div class="togglebutton">
                                            <label>
                                                <input type="checkbox" >
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="form-group">
                                    <label class="col-xs-8 control-label">History Enable</label>
                                    <div class="col-xs-4 control-label text-right">
                                        <div class="togglebutton">
                                            <label>
                                                <input type="checkbox" checked="">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="form-group">
                                    <label class="col-xs-8 control-label">Show Locations</label>
                                    <div class="col-xs-4 control-label text-right">
                                        <div class="togglebutton">
                                            <label>
                                                <input type="checkbox" checked="">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="form-group">
                                    <label class="col-xs-8 control-label">Notifications</label>
                                    <div class="col-xs-4 control-label text-right">
                                        <div class="togglebutton">
                                            <label>
                                                <input type="checkbox" >
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="form-group">
                                    <label class="col-xs-8 control-label">Show Undread Count</label>
                                    <div class="col-xs-4 control-label text-right">
                                        <div class="togglebutton">
                                            <label>
                                                <input type="checkbox" >
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </aside>
        <!--/ RIGHTBAR Content -->
    </div>
    <!--/ CONTROLS Content -->

    <!--  CONTENT  -->
    <section id="content">
        <div class="page page-offcanvas-layout">
            <div id="mapid"></div>
        </div>
    </section>
    <!--/ CONTENT -->
</div>
<!--/ Application Content -->
@stack('modal')
<!--  Vendor JavaScripts  -->
<script src="/assets/bundles/libscripts.bundle.js"></script>
<script src="/assets/bundles/vendorscripts.bundle.js"></script>
<!--/ vendor javascripts -->
<!--  Custom JavaScripts -->
<script src="/assets/js/main.js"></script>

<script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
<script src="https://unpkg.com/leaflet@1.0.2/dist/leaflet.js"></script>
<script>
    $('#mapid').css('height', ($(window).height() - ($('#header').height())));
    $(window).resize(function() {
        $('#mapid').css('height', ($(window).height() - ($('#header').height())));
    });

    var mymap = L.map('mapid', {
        fullscreenControl: true,
    }).setView([{{config('client.map_lat')}}, {{config('client.map_lng')}}], {{config('client.map_zoom')}});

    L.tileLayer('https://api.mapbox.com/styles/v1/seanalaback/ciwtk4ush002o2qrxo43r8o13/tiles/256/{z}/{x}/{y}?access_token={accessToken}', {
        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
        maxZoom: 20,
        accessToken: "{{env('MAPBOX_TOKEN')}}"
    }).addTo(mymap);



    var layers = new Array();

    var dataCollections = new Array();

    function toggleElement(name)
    {
        if(name in dataCollections)
        {
            removeLayer(name);
            return false;
        }
        else{
            return true
        }
    }

    var loadDatasetPoint = function (dataset_id, key) {
        var collectionName = 'dataset-' + dataset_id + '-' + key;
        if(toggleElement(collectionName))
        {
            $(".fa-gear").addClass('fa-spin');
            $.ajax({
                type: 'post',
                url: '{{route('map')}}',
                data: {
                    _token: "{{csrf_token()}}",
                    type: 'datapoint',
                    key: key,
                    dataset_id: dataset_id

                },
                success: function(data) {
                    reloadMap(data['points'], data['title'], data['max'], data['handle'], collectionName)
                },
                error: function(data){
                    $("#settings_cog").removeClass('fa-spin');
                    alert('Oh oh, something went wrong.');
                }
            });

        }

    };

    var loadDataset = function (dataset_id) {
        $(".fa-gear").addClass('fa-spin');

        $.ajax({
            type: 'post',
            url: '/citynexus/reports/views/dot-map',
            data: {
                _token: "{{csrf_token()}}",
                type: 'dataset',
                dataset_id: dataset_id

            },
            success: function(data) {
                reloadMap(data['points'], data['title'], data['max'], data['handle'])
            },
            error: function(data){
                $("#settings_cog").removeClass('fa-spin');
                alert('Oh oh, something went wrong.');
            }
        });

    };

    {{--var loadScore = function (id) {--}}
        {{--$("#settings_cog").addClass('fa-spin');--}}

        {{--$.ajax({--}}
            {{--type: 'post',--}}
            {{--url: '/citynexus/reports/views/dot-map',--}}
            {{--data: {--}}
                {{--_token: "{{csrf_token()}}",--}}
                {{--type: 'score',--}}
                {{--id: id,--}}
            {{--},--}}
            {{--success: function(data) {--}}
                {{--reloadMap(data['points'], data['title'], data['max'], data['handle'])--}}
            {{--},--}}
            {{--error: function(data){--}}
                {{--$("#settings_cog").removeClass('fa-spin');--}}
                {{--alert('Oh oh, something went wrong.');--}}
            {{--}--}}
        {{--});--}}

    {{--};--}}


    var colors = {
        0: {
            layer: null,
            color:'#c93635'
        },
        1: {
            layer: null,
            color: '#003f5e'
        },
        2: {
            layer: null,
            color: '#35c980'
        },
        3: {
            layer: null,
            color: '#5e003f'
        },
        4: {
            layer: null,
            color: '#35c8c9'
        },
        5: {
            layer: null,
            color: '#357ec9'
        }
    };

    var newColor = function(layer)
    {

        for (var i=0; i < Object.keys(colors).length;  ++i)
        {
            if (colors[i]['layer'] == null)
            {
                colors[i].layer = layer;
                return colors[i].color;
            }
        }

    };

    var reloadMap = function(markers, title, max, handle, collection)
    {

        layers[handle] = L.layerGroup();

        var color = newColor(handle);

        for (var i=0; i < markers.length;  ++i)
        {
            layers[handle].addLayer( new L.circleMarker( [markers[i].lat, markers[i].lng], {
                        radius: 4,
                        stroke: false,
                        color: 'red',
                        opacity: 1,
                    } ).bindPopup( markers[i].message )
            );
        }

        layers[handle].addTo(mymap);
        createLayerBox(handle, color, title, markers.length);
        $(".fa-gear").removeClass('fa-spin');

        dataCollections[collection] = layers[handle];

    };

    var createLayerBox = function(layer, color, name, length)
    {
        var box = '<div class="card-box" id="layer_' + layer + '"><span class="fa fa-square" style="color: ' + color + '"></span> <b>' + name + '</b> <small>(' + length + ')</small><span class="fa fa-trash pull-right" style="cursor: pointer" onclick="removeLayer(\'' + layer + '\')"></span></div>';
        $('#layerCards').append(box);
    };

    function removeLayer(layer) {

        dataCollections[layer].clearLayers();
        $('#layer_' + layer).remove();

        for (var i=0; i < Object.keys(colors).length;  ++i)
        {
            if (colors[i]['layer'] == layer)
            {
                colors[i].layer = null;
                break;
            }
        }

        dataCollections = dataCollections.splice(layer, 1);

    };
    </script>

<!--/ custom javascripts -->
</body>
</html>
