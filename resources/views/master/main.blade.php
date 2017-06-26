<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <script>window.Laravel = { csrfToken: '{{ csrf_token() }}' }</script>
    <meta name="_token" content="{{ csrf_token() }}"/>
    <title>CityNexus | @yield('title')</title>
    <link rel="icon" type="image/ico" href="/favicon.ico" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/assets/css/vendor/animsition.min.css">
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/citynexus.css">
    <link rel="stylesheet" href="/css/animate.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">


    @stack('style')

</head>
<body id="body" class="main_Wrapper theme-default header-fixed aside-fixed rightmenu-hidden leftmenu-sm">

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
            <form action="{{route('search.search')}}" class="search" id="main-search">
                <input id="search-bar" type="text" name="query" class="typeahead form-control underline-input" placeholder="Search everything...">
                <input type="submit" style="display:none"/>
            </form>
            <!-- Search end -->

            <!-- Right-side navigation -->
            <ul class="nav-right pull-right list-inline">
                <li class="dropdown notifications"> <a href class="dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-bell"></i>
                        @if(\Illuminate\Support\Facades\Auth::user()->unreadNotifications->count() > 0)<div class="notify"><span class="heartbit"></span><span class="point"></span></div>@endif
                    </a>
                    @include('master.notifications._notifications')
                </li>
                {{--<li class="toggle-right-leftmenu"><a role="button" tabindex="0"><i class="fa fa-gear"></i></a></li>--}}
                <li><a href="/auth/logout"><i class="fa fa-sign-out"></i> Log Out</a></li>
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
                    {{--<div class="panel settings panel-default">--}}
                        {{--<div class="panel-heading" role="tab">--}}
                            {{--<h4 class="panel-title"><a data-toggle="collapse" href="#leftmenuControls">General Settings <i class="fa fa-angle-up"></i></a></h4>--}}
                        {{--</div>--}}
                        {{--<div id="leftmenuControls" class="panel-collapse collapse in" role="tabpanel">--}}
                            {{--<div class="panel-body">--}}
                                {{--<div class="form-group">--}}
                                    {{--<div class="row">--}}
                                        {{--<label class="col-xs-8">Switch ON</label>--}}
                                        {{--<div class="col-xs-4 control-label">--}}
                                            {{--<div class="togglebutton">--}}
                                                {{--<label>--}}
                                                    {{--<input type="checkbox" checked="">--}}
                                                {{--</label>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="form-group">--}}
                                    {{--<div class="row">--}}
                                        {{--<label class="col-xs-8">Switch OFF</label>--}}
                                        {{--<div class="col-xs-4 control-label">--}}
                                            {{--<div class="togglebutton">--}}
                                                {{--<label>--}}
                                                    {{--<input type="checkbox" >--}}
                                                {{--</label>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="milestone-sidbar">--}}
                            {{--<div class="text-center-folded"> <span class="pull-right pull-none-folded">60%</span> <span class="hidden-folded">Milestone</span> </div>--}}
                            {{--<div class="progress progress-xxs m-t-sm dk">--}}
                                {{--<div class="progress-bar progress-bar-info" style="width: 60%;"> </div>--}}
                            {{--</div>--}}
                            {{--<div class="text-center-folded"> <span class="pull-right pull-none-folded">35%</span> <span class="hidden-folded">Release</span> </div>--}}
                            {{--<div class="progress progress-xxs m-t-sm dk">--}}
                                {{--<div class="progress-bar progress-bar-primary" style="width: 35%;"> </div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                </div>
            </div>
        </aside>
        <!--/ SIDEBAR Content -->

        <!--RIGHTBAR Content -->
        <aside id="rightmenu">
            <div role="tabpanel">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#chat" aria-controls="chat" role="tab" data-toggle="tab">Chat</a></li>
                    <li role="presentation"><a href="#todo" aria-controls="todo" role="tab" data-toggle="tab">Todo</a></li>
                    <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Settings</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="chat">
                        <div class="search">
                            <div class="form-group is-empty">
                                <input type="text" class="form-control underline-input" placeholder="Search...">
                                <span class="material-input"></span></div>
                        </div>
                        <h6>Recent</h6>
                        <ul>


                        </ul>
                        <h6>Contacts</h6>
                        <ul>

                        </ul>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="todo">
                        <div class="form-group">
                            <input type="text" value="" placeholder="Create new task..." class="form-control" />
                            <span class="fa fa-plus"></span> </div>
                        <h6>Today</h6>
                        <ul class="todo-list">
                            <li>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="optionsCheckboxes">
                                        Initialize the project</label>
                                </div>
                            </li>
                            <li>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="optionsCheckboxes">
                                        Create the main structure</label>
                                </div>
                            </li>
                            <li>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="optionsCheckboxes">
                                        Create the main structure</label>
                                </div>
                            </li>
                        </ul>
                        <h6>Tomorrow</h6>
                        <ul class="todo-list">
                            <li>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="optionsCheckboxes">
                                        Initialize the project</label>
                                </div>
                            </li>
                            <li>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="optionsCheckboxes">
                                        Create the main structure</label>
                                </div>
                            </li>
                            <li>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="optionsCheckboxes">
                                        displayed in a normal space!</label>
                                </div>
                            </li>
                        </ul>
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
            @yield('main')
        </div>
    </section>
    <!--/ CONTENT -->
</div>
<!--/ Application Content -->
@stack('modal')
<!--  Vendor JavaScripts  -->

<script src="/js/app.js"></script>

<script src="/assets/bundles/libscripts.bundle.js"></script>
<script src="/assets/bundles/vendorscripts.bundle.js"></script>
<!--/ vendor javascripts -->
<!--  Custom JavaScripts -->
<script src="/assets/js/main.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js"></script>
<script src="/js/bootstrap-notify.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js" integrity="sha256-+mWd/G69S4qtgPowSELIeVAv7+FuL871WXaolgXnrwQ=" crossorigin="anonymous"></script>



@include('master.snipits._alerts')
@include('master._form_error')
<script>
    var results = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        // url points to a json file that contains an array of country names, see
        // https://github.com/twitter/typeahead.js/blob/gh-pages/data/countries.json
        prefetch: '{{route('search.suggestions')}}',
        remote: {
            url: '{{route('search.suggestions')}}/%QUERY',
            wildcard: '%QUERY'

        }
    });

    // passing in `null` for the `options` arguments will result in the default
    // options being used
    $('#main-search .typeahead').typeahead(null, {
        name: 'results',
        source: results
    });
</script>

@stack('scripts')

<!--/ custom javascripts -->
</body>
</html>
