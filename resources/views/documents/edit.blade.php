@extends('master.main')

@section('title', 'Edit ' . ' letter')

@section('main')

    <div class="col-sm-8">
        <div class="boxs">
            <div class="boxs-header">
                <h1 class="custom-font">Letter Edit</h1>
            </div>
            <div class="boxs-body">
                <div id="editor">
                    {!! $document->body!!}
                </div>
            </div>
            <div class="boxs-footer">
                <form class="form">
                    {{csrf_field()}}
                    <button class="btn btn-primary btn-raised">Queue Letter for Printing</button>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('style')

    <link href="//cdn.quilljs.com/1.2.6/quill.snow.css" rel="stylesheet">
    <link href="//cdn.quilljs.com/1.2.6/quill.bubble.css" rel="stylesheet">

@endpush

@push('scripts')

<script src="//cdn.quilljs.com/1.2.6/quill.js"></script>
<script src="//cdn.quilljs.com/1.2.6/quill.min.js"></script>

<script>
    var quill = new Quill('#editor', {
        theme: 'snow',
    });

    quill.on('text-change', function () {
        $('#body').val(quill.root.innerHTML);
    });

    function queueLetter()
    {
        $
    }

</script>

@endpush