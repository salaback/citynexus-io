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
                    {{title_case($property->address)}}
                </span>

            </div>
            <div class="col-md-4">
                <!-- boxs -->
                @if($property->units->count() > 0)
                    @include('property.snipits._units', ['units' => $property->units()->orderBy('unit')->get()])
                @endif
                <!-- /boxs -->

                <!-- boxs -->
                <section class="boxs boxs-simple">
                    <!-- boxs header -->
                    <div class="boxs-header dvd dvd-btm">
                        <h1 class="custom-font"><strong>My</strong> Portfolio Status</h1>
                    </div>
                    <!-- /boxs header -->
                    <!-- boxs body -->
                    <div class="boxs-body">
                        <ul class="list-unstyled">
                            <li>
                                <div class="row">
                                    <div class="col-md-12">Behance</div>
                                    <div class="col-md-12">
                                        <div class="progress progress-striped0 mb-10">
                                            <div class="progress-bar progress-bar-blue" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width: 90%"> 93% </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="row">
                                    <div class="col-md-12">Themeforest</div>
                                    <div class="col-md-12">
                                        <div class="progress progress-striped0 mb-10">
                                            <div class="progress-bar progress-bar-green" role="progressbar" aria-valuenow="63" aria-valuemin="0" aria-valuemax="100" style="width: 63%"> 63% </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="row">
                                    <div class="col-md-12">Dribbble</div>
                                    <div class="col-md-12">
                                        <div class="progress progress-striped0 mb-10">
                                            <div class="progress-bar progress-bar-hotpink" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%"> 60% </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="row">
                                    <div class="col-md-12">Pinterest</div>
                                    <div class="col-md-12">
                                        <div class="progress progress-striped0 mb-0">
                                            <div class="progress-bar progress-bar-lightred" role="progressbar" aria-valuenow="76" aria-valuemin="0" aria-valuemax="100" style="width: 76%"> 76% </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <!-- /boxs body -->
                </section>
                <!-- /boxs -->
            </div>
            <div class="col-md-8">
                <!-- boxs -->
                <section class="boxs boxs-simple">
                    <!-- boxs body -->
                    <div class="boxs-body p-0">
                        <div role="tabpanel">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs tabs-dark-t" role="tablist">
                                <li role="presentation" class="active"><a href="#datasets" aria-controls="feedTab" role="tab" data-toggle="tab">Data Sets</a></li>
                                <li role="presentation"><a href="#notes" aria-controls="timeline" role="tab" data-toggle="tab">Notes</a></li>
                                <li role="presentation"><a href="#files" aria-controls="setting" role="tab" data-toggle="tab">Files</a></li>
                                <li role="presentation"><a href="#actions" aria-controls="setting" role="tab" data-toggle="tab">Actions</a></li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="datasets">
                                    <div class="wrap-reset">
                                        <div class="mypost-form">
                                            <textarea class="form-control br-0-t" placeholder="What's up dude?" rows="5"></textarea>
                                            <div class="post-toolbar-b">
                                                <a href="#" tooltip="Add File" class="btn btn-raised btn-primary btn-sm"><i class="fa fa-paperclip"></i></a>
                                                <a href="#" tooltip="Add Image" class="btn btn-raised btn-default btn-sm"><i class="fa fa-camera"></i></a>
                                                <a href="#" class="pull-right btn btn-raised btn-info btn-sm" tooltip="Post it!"><i class="fa fa-share mr-10"></i>Post</a>
                                            </div>
                                        </div>
                                        <div class="mypost-list mt-20">
                                            <div class="post-box">
                                                <span class="text-muted text-small"><i class="fa fa-clock-o mr-5"></i> 3 minutes ago</span>
                                                <div class="post-img"><img src="/assets/images/puppy-1.jpg" class="img-responsive" alt /></div>
                                                <div class="panel panel-default">
                                                    <h3 class="">Lorem Ipsum is simply dummy text of the printing</h3>
                                                    <p class="mb-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, </p>
                                                    <p class="mt-10 mb-0">
                                                        <a href="" class="btn btn-raised bg-blue btn-sm"> <i class="fa fa-heart-o text-inactive"></i> Like (5) </a>
                                                        <a href="" class="btn btn-raised bg-soundcloud btn-sm"><i class="fa fa-mail-reply"></i> Reply</a>
                                                    </p>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="post-box">
                                                <span class="text-muted text-small"><i class="fa fa-clock-o mr-5"></i> 23 minutes ago</span>
                                                <div class="post-img"><img src="/assets/images/puppy-2.jpg" class="img-responsive" alt /></div>
                                                <div class="panel panel-default">
                                                    <h3 class="">Lorem Ipsum is simply dummy text of the printing</h3>
                                                    <p class="mb-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, </p>
                                                    <p class="mt-10 mb-0">
                                                        <a href="" class="btn btn-raised bg-blue btn-sm"> <i class="fa fa-heart-o text-inactive"></i> Like (5) </a>
                                                        <a href="" class="btn btn-raised bg-soundcloud btn-sm"><i class="fa fa-mail-reply"></i> Reply</a>
                                                    </p>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="post-box">
                                                <span class="text-muted text-small"><i class="fa fa-clock-o mr-5"></i> 45 minutes ago</span>
                                                <div class="post-img"><img src="/assets/images/puppy-3.jpg" class="img-responsive" alt /></div>
                                                <div class="panel panel-default">
                                                    <h3 class="">Lorem Ipsum is simply dummy text of the printing</h3>
                                                    <p class="mb-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, </p>
                                                    <p class="mt-10 mb-0">
                                                        <a href="" class="btn btn-raised bg-blue btn-sm"> <i class="fa fa-heart-o text-inactive"></i> Like (5) </a>
                                                        <a href="" class="btn btn-raised bg-soundcloud btn-sm"><i class="fa fa-mail-reply"></i> Reply</a>
                                                    </p>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="text-center"> <a href="#" class="btn btn-raised btn-default btn-sm">Load More …</a> </div>
                                        </div>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="timeline">
                                    <div class="timeline-body">
                                        <div class="timeline m-border">
                                            <div class="timeline-item">
                                                <div class="item-content">
                                                    <div class="text-small">Just now</div>
                                                    <p>Finished task #features 4.</p>
                                                </div>
                                            </div>
                                            <div class="timeline-item border-info">
                                                <div class="item-content">
                                                    <div class="text-small">11:30</div>
                                                    <p>@Jessi retwit your post</p>
                                                </div>
                                            </div>
                                            <div class="timeline-item border-warning border-l">
                                                <div class="item-content">
                                                    <div class="text-small">10:30</div>
                                                    <p>Call to customer #Jacob and discuss the detail.</p>
                                                </div>
                                            </div>
                                            <div class="timeline-item border-warning">
                                                <div class="item-content">
                                                    <div class="text-small">3 days ago</div>
                                                    <p>Jessi commented your post.</p>
                                                </div>
                                            </div>
                                            <div class="timeline-item border-danger">
                                                <div class="item-content">
                                                    <div class="text--muted">Thu, 10 Mar</div>
                                                    <p>Trip to the moon</p>
                                                </div>
                                            </div>
                                            <div class="timeline-item border-info">
                                                <div class="item-content">
                                                    <div class="text-small">Sat, 5 Mar</div>
                                                    <p>Prepare for presentation</p>
                                                </div>
                                            </div>
                                            <div class="timeline-item border-danger">
                                                <div class="item-content">
                                                    <div class="text-small">Sun, 11 Feb</div>
                                                    <p>Jessi assign you a task #Mockup Design.</p>
                                                </div>
                                            </div>
                                            <div class="timeline-item border-info">
                                                <div class="item-content">
                                                    <div class="text-small">Thu, 17 Jan</div>
                                                    <p>Follow up to close deal</p>
                                                </div>
                                            </div>
                                            <div class="timeline-item">
                                                <div class="item-content">
                                                    <div class="text-small">Just now</div>
                                                    <p>Finished task #features 4.</p>
                                                </div>
                                            </div>
                                            <div class="timeline-item border-info">
                                                <div class="item-content">
                                                    <div class="text-small">11:30</div>
                                                    <p>@Jessi retwit your post</p>
                                                </div>
                                            </div>
                                            <div class="timeline-item border-warning border-l">
                                                <div class="item-content">
                                                    <div class="text-small">10:30</div>
                                                    <p>Call to customer #Jacob and discuss the detail.</p>
                                                </div>
                                            </div>
                                            <div class="timeline-item border-warning">
                                                <div class="item-content">
                                                    <div class="text-small">3 days ago</div>
                                                    <p>Jessi commented your post.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="setting">
                                    <div class="wrap-reset">
                                        <form class="profile-settings">
                                            <div class="row">
                                                <div class="form-group col-md-12 legend">
                                                    <h4><strong>Security</strong> Settings</h4>
                                                    <p>Secure your account</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-sm-6">
                                                    <label for="username">Username</label>
                                                    <input type="text" class="form-control" id="username" value="JonathanSmith" readonly>
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label for="password">Current Password</label>
                                                    <input type="password" class="form-control" id="password" value="secretpassword" readonly>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-sm-6">
                                                    <label for="new-password">New Password</label>
                                                    <input type="password" class="form-control" id="new-password">
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label for="new-password-repeat">New Password Repeat</label>
                                                    <input type="password" class="form-control" id="new-password-repeat">
                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <button class="btn btn-raised btn-primary btn-sm">Save Changes</button>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="form-group col-md-12 legend">
                                                    <h4><strong>Account</strong> Settings</h4>
                                                    <p>Your personal account settings</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-sm-6">
                                                    <label for="first-name">First Name</label>
                                                    <input type="text" class="form-control" id="first-name" value="Jonathan">
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label for="last-name">Last Name</label>
                                                    <input type="text" class="form-control" id="last-name" value="Smith">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-sm-12">
                                                    <label for="address1">Address Line 1</label>
                                                    <input type="text" class="form-control" id="address1" value="Lorem Ipsum 215">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-sm-4">
                                                    <label for="city">City</label>
                                                    <input type="text" class="form-control" id="city" value="New Yourk">
                                                </div>
                                                <div class="form-group col-sm-4">
                                                    <label for="email">E-mail</label>
                                                    <input type="email" class="form-control" id="email" value="Jonathan.s@infowy.com" readonly>
                                                </div>
                                                <div class="form-group col-sm-4">
                                                    <label for="country">Country</label>
                                                    <select id="country" class="chosen-select" style="width: 100%;">
                                                        <option>USA</option>
                                                        <option>Canada</option>
                                                        <option>UK</option>
                                                        <option>India</option>
                                                        <option>Japan</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="form-group col-md-12 legend">
                                                    <h4><strong>Social</strong> Settings</h4>
                                                    <p>Connect with your social profiles</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-sm-6">
                                                    <label for="facebook">Facebook</label>
                                                    <input type="text" class="form-control" id="facebook">
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label for="dribbble">Dribbble</label>
                                                    <input type="text" class="form-control" id="dribbble">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-sm-6">
                                                    <label for="pinterest">Twitter</label>
                                                    <input type="text" class="form-control" id="Twitter">
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label for="flickr">Instagram</label>
                                                    <input type="text" class="form-control" id="Instagram">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-sm-6">
                                                    <label for="pinterest">Pinterest</label>
                                                    <input type="text" class="form-control" id="pinterest">
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label for="flickr">Behance</label>
                                                    <input type="text" class="form-control" id="Behance">
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group checkbox mt-0">
                                                        <label>
                                                            <input type="checkbox" name="optionsCheckboxes">
                                                            Profile Visibility For Everyone
                                                        </label>
                                                    </div>
                                                    <div class="form-group checkbox">
                                                        <label>
                                                            <input type="checkbox" name="optionsCheckboxes" checked>
                                                            New task notifications
                                                        </label>
                                                    </div>
                                                    <div class="form-group checkbox">
                                                        <label>
                                                            <input type="checkbox" name="optionsCheckboxes">
                                                            New friend request notifications
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <button class="btn btn-raised btn-default">Cancel</button>
                                                    <button class="btn btn-raised btn-primary">Save All</button>
                                                </div>
                                            </div>
                                        </form>
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

@endpush