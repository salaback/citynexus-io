@extends('master.main')

@section('title', title_case($entity->name))

@section('main')
    <div class="page profile-page">
        <!-- page content -->
        <div class="pagecontent">

            <div class="col-xs-12">
                <h1 class="font-thin h3 m-0">{{$entity->name}}</h1>
                <small class="text-muted"></small>
            </div>

            <br>
            <br>

            <!-- row -->
            <div class="row">
                <div class="col-md-4">

                    <!-- boxs -->
                @if($entity->properties->count() > 0)
                    <div class="boxs">
                        <div class="boxs-header">
                            <h1 class="custom-font"><strong>Related</strong> Properties</h1>
                        </div>
                        <div class="box-body">
                            <div class="list-group">
                                @include('entity.snipits._properties', ['properties' => $entity->properties->sortBy('address')])
                            </div>
                        </div>
                    </div>
                @endif
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
                                    <li role="presentation" @if(!isset($_GET['tab'])) class="active" @endif><a href="#datasets" aria-controls="datasets" role="tab" data-toggle="tab">Data Sets</a></li>
                                    <li role="presentation" @if(isset($_GET['tab']) && $_GET['tab'] == 'comments') class="active" @endif><a href="#comments" aria-controls="comments" role="tab" data-toggle="tab">Comments</a></li>
                                    <li role="presentation"><a href="#actions" aria-controls="actions" role="tab" data-toggle="tab">Actions</a></li>
                                </ul>

                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane @if(!isset($_GET['tab'])) active @endif" id="datasets">
                                        <div class="wrap-reset">
                                            @include('entity.snipits._datasets')
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane @if(isset($_GET['tab']) && $_GET['tab'] == 'comments') active @endif" id="comments">
                                        <div class="wrap-reset">
                                            @include('snipits._comments', ['comments' => $entity->comments, 'model' => 'App\\\PropertyMgr\\\Model\\\Entity', 'model_id' => $entity->id])
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