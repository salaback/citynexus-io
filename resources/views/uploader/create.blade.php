@extends("master.main")

@section('title', "Create New Data Set")

@section('main')

    <div class="col-sm-offset-1 col-sm-10">
        <section class="boxs">
            <div class="boxs-body">
                <div class="slide-contents" id="slide-content">
                    @include('uploader.slides.information')
                </div>
            </div>
        </section>
    </div>

@endsection

@push('style')
<link href="/vendor/fileuploads/css/dropify.min.css" rel="stylesheet" type="text/css" />

@endpush
@push('scripts')

<script src="/vendor/fileuploads/js/dropify.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.5.7/jquery.fileupload.js"></script>

@include("uploader.javascript")

@endpush