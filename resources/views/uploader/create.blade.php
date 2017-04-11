@extends("master.main")

@section('title', "Create New Data Set")

@section('main')

    <!-- BASIC WIZARD -->
    <div class="col-sm-offset-2 col-sm-8">
        <!-- boxs -->
        <section class="boxs boxs-simple" id="slide-card">
            <!-- boxs body -->
            <div class="boxs-body">
                <div class="slide-contents" id="slide-content">
                        @include('uploader.slides.information')
                </div>
            </div>
        </section>
    </div>
    <!-- end col -->

@endsection

@push('style')
<link href="/plugins/fileuploads/css/dropify.min.css" rel="stylesheet" type="text/css" />

@endpush
@push('javascript')

<script src="/plugins/bootstrap-wizard/jquery.bootstrap.wizard.js"></script>
<script src="/plugins/jquery-validation/dist/jquery.validate.min.js"></script>
<script src="/plugins/fileuploads/js/dropify.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.5.7/jquery.fileupload.js"></script>

@include("uploader.javascript")

@endpush