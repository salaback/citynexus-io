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

            </div>
            <div class="col-md-8">
                <!-- boxs -->
                <section class="boxs boxs-simple">
                    <!-- boxs body -->
                    <div class="boxs-body p-0">
                        <div role="tabpanel">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs tabs-dark-t" role="tablist">
                                <li role="presentation" class="active"><a href="#datasets" aria-controls="datasets" role="tab" data-toggle="tab">Data Sets</a></li>
                                <li role="presentation"><a href="#notes" aria-controls="notes" role="tab" data-toggle="tab">Notes</a></li>
                                <li role="presentation"><a href="#files" aria-controls="files" role="tab" data-toggle="tab">Files</a></li>
                                <li role="presentation"><a href="#actions" aria-controls="actions" role="tab" data-toggle="tab">Actions</a></li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="datasets">
                                    <div class="wrap-reset">
                                        @include('property.snipits._datasets')
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="timeline">
                                    <div class="wrap-reset">
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="setting">
                                    <div class="wrap-reset">
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