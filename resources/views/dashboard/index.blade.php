<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>:: Oakleaf - Admin Dashboard ::</title>
    <link rel="icon" type="image/ico" href="assets/images/favicon.ico" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/assets/css/vendor/animsition.min.css">

    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body id="oakleaf" class="main_Wrapper leftmenu-offcanvas">

<!--  Application Content -->
<div id="wrap" class="animsition">

    <!--  HEADER Content  -->
    <section id="header">
        <header class="clearfix">
            <!-- Branding -->
            <div class="branding"> <a class="brand" href="index.html"><span>Oakleaf</span></a> <a role="button" tabindex="0" class="offcanvas-toggle visible-xs-inline"><i class="fa fa-bars"></i></a> </div>
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
                <li class="dropdown nav-profile"> <a href class="dropdown-toggle" data-toggle="dropdown"> <img src="assets/images/profile-photo.jpg" alt="" class="0 size-30x30"></a>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <div class="user-info">
                                <div class="user-name">Jonathan Smith</div>
                                <div class="user-position online">Available</div>
                            </div>
                        </li>
                        <li><a href="profile.html" role="button" tabindex="0"><span class="label label-success pull-right">80%</span><i class="fa fa-user"></i>Profile</a></li>
                        <li><a role="button" tabindex="0"><span class="label label-info pull-right">new</span><i class="fa fa-check"></i>Tasks</a></li>
                        <li> <a role="button" tabindex="0"><i class="fa fa-cog"></i>Settings</a></li>
                        <li class="divider"></li>
                        <li><a href="locked.html" role="button" tabindex="0"><i class="fa fa-lock"></i>Lock</a></li>
                        <li><a href="login.html" role="button" tabindex="0"><i class="fa fa-sign-out"></i>Logout</a></li>
                    </ul>
                </li>
                <li class="dropdown users"> <a href class="dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-th"></i>
                    </a>
                    <div class="dropdown-menu pull-right with-arrow panel panel-default" role="menu">
                        <ul class="app-sortcut">
                            <li>
                                <a href="#" class="connection-item">
                                    <i class="fa  fa-umbrella"></i>
                                    <span class="block">weather</span>
                                </a>
                            </li>
                            <li>
                                <a href="drive.html" class="connection-item">
                                    <i class="fa fa-cloud-upload"></i>
                                    <span class="block">Drive</span>
                                </a>
                            </li>
                            <li>
                                <a href="calendar.html" class="connection-item">
                                    <i class="fa fa-calendar-check-o"></i>
                                    <span class="block">calendar</span>
                                </a>
                            </li>
                            <li>
                                <a href="maps-google.html" class="connection-item">
                                    <i class="fa fa-map-o"></i>
                                    <span class="block">map</span>
                                </a>
                            </li>
                            <li>
                                <a href="chat.html" class="connection-item">
                                    <i class="fa fa-comments-o"></i>
                                    <span class="block">chat</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="connection-item">
                                    <i class="fa fa-book"></i>
                                    <span class="block">contact</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="dropdown messages"> <a href class="dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-envelope"></i>
                        <div class="notify"><span class="heartbit"></span><span class="point"></span></div>
                    </a>
                    <div class="dropdown-menu pull-right with-arrow panel panel-default" role="menu">
                        <ul class="list-group">
                            <li class="list-group-item"> <a role="button" tabindex="0" class="media"> <span class="pull-left media-object thumb thumb-sm"> <img src="assets/images/pi-avatar.jpg" alt="" class=""> </span>
                                    <div class="media-body"> <span class="block">Lucas sent you a message</span> <small class="text-muted">9 minutes ago</small> </div>
                                </a> </li>
                            <li class="list-group-item"> <a role="button" tabindex="0" class="media"> <span class="pull-left media-object  thumb thumb-sm"> <img src="assets/images/Jane-avatar.jpg" alt="" class=""> </span>
                                    <div class="media-body"> <span class="block">Jane sent you a message</span> <small class="text-muted">27 minutes ago</small> </div>
                                </a> </li>
                            <li class="list-group-item"> <a role="button" tabindex="0" class="media"> <span class="pull-left media-object  thumb thumb-sm"> <img src="assets/images/random-avatar1.jpg" alt="" class=""> </span>
                                    <div class="media-body"> <span class="block">Lee sent you a message</span> <small class="text-muted">2 hour ago</small> </div>
                                </a> </li>
                            <li class="list-group-item"> <a role="button" tabindex="0" class="media"> <span class="pull-left media-object  thumb thumb-sm"> <img src="assets/images/random-avatar3.jpg" alt="" class=""> </span>
                                    <div class="media-body"> <span class="block">Rihtik sent you a message</span> <small class="text-muted">8 hours ago</small> </div>
                                </a> </li>
                        </ul>
                        <div class="panel-footer"> <a role="button" tabindex="0">Show all messages <i class="pull-right fa fa-angle-right"></i></a> </div>
                    </div>
                </li>
                <li class="dropdown notifications"> <a href class="dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-bell"></i>
                        <div class="notify"><span class="heartbit"></span><span class="point"></span></div>
                    </a>
                    <div class="dropdown-menu pull-right with-arrow panel panel-default ">
                        <ul class="list-group">
                            <li class="list-group-item"> <a role="button" tabindex="0" class="media"> <span class="pull-left media-object media-icon"> <i class="fa fa-ban"></i> </span>
                                    <div class="media-body"> <span class="block">User Lucas cancelled account</span> <small class="text-muted">12 minutes ago</small> </div>
                                </a> </li>
                            <li class="list-group-item"> <a role="button" tabindex="0" class="media"> <span class="pull-left media-object media-icon"> <i class="fa fa-spotify"></i> </span>
                                    <div class="media-body"> <span class="block">2 voice mails</span> <small class="text-muted">Neque porro quisquam est</small> </div>
                                </a> </li>
                            <li class="list-group-item"> <a role="button" tabindex="0" class="media"> <span class="pull-left media-object media-icon"> <i class="fa fa-whatsapp"></i> </span>
                                    <div class="media-body"> <span class="block">8 voice messanger</span> <small class="text-muted">8 texts</small> </div>
                                </a> </li>
                        </ul>
                        <div class="panel-footer"> <a role="button" tabindex="0">Show all notifications <i class="fa fa-angle-right pull-right"></i></a> </div>
                    </div>
                </li>
                <li class="toggle-right-leftmenu"><a role="button" tabindex="0"><i class="fa fa-gear"></i></a></li>
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
                            <li class="online">
                                <div class="media"> <a class="pull-left thumb thumb-sm" role="button" tabindex="0"> <img class="media-object " src="assets/images/pi-avatar.jpg" alt=""> </a>
                                    <div class="media-body"> <span class="name">Claire Sassu</span> <span class="message">Can you share the...</span> <span class="badge badge-outline status"></span> </div>
                                </div>
                            </li>
                            <li class="online">
                                <div class="media"> <a class="pull-left thumb thumb-sm" role="button" tabindex="0"> <img class="media-object " src="assets/images/John-avatar.jpg" alt=""> </a>
                                    <div class="media-body">
                                        <div class="media-body"> <span class="name">Maggie jackson</span> <span class="message">Can you share the...</span> <span class="badge badge-outline status"></span> </div>
                                    </div>
                                </div>
                            </li>
                            <li class="online">
                                <div class="media"> <a class="pull-left thumb thumb-sm" role="button" tabindex="0"> <img class="media-object " src="assets/images/Jane-avatar.jpg" alt=""> </a>
                                    <div class="media-body">
                                        <div class="media-body"> <span class="name">Joel King</span> <span class="message">Ready for the meeti...</span> <span class="badge badge-outline status"></span> </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <h6>Contacts</h6>
                        <ul>
                            <li class="offline">
                                <div class="media"> <a class="pull-left thumb thumb-sm" role="button" tabindex="0"> <img class="media-object " src="assets/images/random-avatar4.jpg" alt=""> </a>
                                    <div class="media-body">
                                        <div class="media-body"> <span class="name">Joel King</span> <span class="badge badge-outline status"></span> </div>
                                    </div>
                                </div>
                            </li>
                            <li class="online">
                                <div class="media"> <a class="pull-left thumb thumb-sm" role="button" tabindex="0"> <img class="media-object " src="assets/images/random-avatar5.jpg" alt=""> </a>
                                    <div class="media-body">
                                        <div class="media-body"> <span class="name">Joel King</span> <span class="badge badge-outline status"></span> </div>
                                    </div>
                                </div>
                            </li>
                            <li class="offline">
                                <div class="media"> <a class="pull-left thumb thumb-sm" role="button" tabindex="0"> <img class="media-object " src="assets/images/random-avatar6.jpg" alt=""> </a>
                                    <div class="media-body">
                                        <div class="media-body"> <span class="name">Joel King</span> <span class="badge badge-outline status"></span> </div>
                                    </div>
                                </div>
                            </li>
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
        </div>
    </section>
    <!--/ CONTENT -->
</div>
<!--/ Application Content -->

<!--  Vendor JavaScripts  -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.2.4/vue.js"></script>
<script src="/assets/bundles/libscripts.bundle.js"></script>
<script src="/assets/bundles/vendorscripts.bundle.js"></script>
<!--/ vendor javascripts -->
<!--  Custom JavaScripts -->
<script src="/assets/js/main.js"></script>
<script src="/js/all.js"></script>
<!--/ custom javascripts -->
</body>
</html>
