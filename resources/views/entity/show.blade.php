@extends('master.main')

@section('title', title_case($entity->name))

@section('main')
    <div class="page profile-page">
        <!-- page content -->
        <div class="pagecontent">

            <div class="col-xs-12">
                <h1 class="font-thin h3 m-0">{{$entity->name}} <small><i role="button" class="fa fa-pencil" data-toggle="modal" data-target="#editEntity"></i></small></h1>
                @include('snipits._tags', ['tags' => $entity->tags, 'trashedTags' => $entity->trashedTags])
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
                                    <li role="presentation" @if(isset($_GET['tab']) && $_GET['tab'] == 'tasks') class="active" @endif><a href="#tasks" aria-controls="comments" role="tab" data-toggle="tab">Tasks</a></li>
                                @can('citynexus', ['files', 'view']) <li role="presentation" @if(isset($_GET['tab']) && $_GET['tab'] == 'files') class="active" @endif><a href="#files" aria-controls="files" role="tab" data-toggle="tab">Files</a></li> @endcan
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
                                    <div role="tabpanel" class="tab-pane @if(isset($_GET['tab']) && $_GET['tab'] == 'files') @endif" id="files">
                                        <div class="wrap-reset">
                                            @include('snipits._files', ['files' => $entity->files, 'model_id' => $entity->id, 'model_type' => 'App\\PropertyMgr\\Model\\Entity'])
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane @if(isset($_GET['tab']) && $_GET['tab'] == 'tasks') active @endif" id="tasks">
                                        <div class="wrap-reset">
                                            @include('snipits._tasks', ['lists' => $entity->tasks, 'model_type' => 'App\\\PropertyMgr\\\Model\\\Entity', 'model_id' => $entity->id])
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane @if(isset($_GET['tab']) && $_GET['tab'] == 'actions') active @endif" id="actions">
                                        <div class="wrap-reset">
                                            @include('entity.snipits._actions')
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

@push('modal')

<div class="modal fade" id="editEntity">
	<div class="modal-dialog">
		<div class="modal-content">
            <form action="{{route('entity.store', [$entity->id])}}" method="POST" role="form">

            <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Edit Entity</h4>
			</div>
			<div class="modal-body">
                    {{csrf_field()}}
                    {{method_field('patch')}}

					<div class="form-group">
						<label for="first_name">First Name</label>
						<input type="text" class="form-control" name="first_name" id="first_name" value="{{$entity->first_name}}">
					</div>
                    <div class="form-group">
                        <label for="middle_name">Middle Name</label>
                        <input type="text" name="middle_name" id="middle_name" class="form-control" value="{{$entity->middle_name}}">
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" class="form-control" name="last_name" id="last_name" value="{{$entity->last_name}}">
                    </div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-raised" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary btn-raised">Save changes</button>
			</div>
            </form>
        </div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@endpush
