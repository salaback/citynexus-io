@extends("master.main")

@section('title', "Create New Data Set")

@section('main')

    <!-- BASIC WIZARD -->
    <div class="col-lg-offset-1 col-lg-10 animated" id="slide-card">
        <div class="card-box p-b-0">
            <div class="slide-contents" id="slide-content">
                <div class="row">
                    @include('uploader.slides.information')
                </div>
            </div>
        </div>
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