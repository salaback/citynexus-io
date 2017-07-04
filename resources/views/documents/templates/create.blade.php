@extends('master.main')

@section('title', 'Create New Document Template')

@section('main')

    <div class="col-xs-12">
        <h1 class="font-thin h3 m-0">Create Document Template</h1>
    </div>
    <form action="{{route('templates.store')}}" method="post">

    <div class="boxs">
        <div class="boxs-body">
                {{csrf_field()}}
                <input type="hidden" id="body-content" name="body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">
                                Document Name
                            </label>
                            <input type="text" name="name" class="form-control" id="name" value="{{old('name')}}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="type">
                                Document Type
                            </label>

                            <select name="type" id="type" class="form-control">
                                    <option value="">Select One</option>
                                    <option value="letter">Letter</option>
                                    <option value="email">Email</option>
                                </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="relations" class="">
                            Make Available From:
                        </label>
                        <div class='togglebutton'>
                            <label>
                                <input type="checkbox" name="visible_on[buildings]" id="buildings" value="true">
                                Buildings</label>
                        </div>
                        <div class='togglebutton'>
                            <label>
                                <input type="checkbox" name="visible_on[units]" id="units" value="true">
                                Units</label>
                        </div>
                        <div class='togglebutton'>
                            <label>
                                <input type="checkbox" name="visible_on[entities]" id="entities" value="true">
                                Entities</label>
                        </div>
                    </div>
                </div>
            <input type="submit" class="btn btn-primary btn-raised" value="Create Template">
        </div>
    </div>
    </form>

    <div class="row">
        <div class="col-sm-4">
            <div class="boxs">
                <div class="boxs-body">
                    @include('documents.templates._variables')
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="boxs">
                <div class="boxs-header">
                    <h1 class="custom-font">Template Content</h1>
                </div>
                <div class="boxs-body">
                    <div id="editor">
                        <p><<- entity:full_name ->></p>
                        <p><<- entity:mailing_address ->></p>
                        <p><br></p>
                        <p><<- entity:full_name ->>;</p>
                        <p><br></p>
                        <p>This letter is to inform you of an issue in unit  <<- unit:unit ->> of your building at  <<- building:address ->>.</p>
                        <p><br></p>
                        <p>Please contact me at your earliest convenience.</p>
                        <p><br></p>
                        <p>Regards,</p>
                        <p><br></p>
                        <p><<- sender:full_name ->></p>
                        <p><<- sender:title ->>, <<- sender:department ->></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endsection

@push('scripts')

<script src="//cdn.quilljs.com/1.2.6/quill.js"></script>
<script src="//cdn.quilljs.com/1.2.6/quill.min.js"></script>

<script>
    var quill = new Quill('#editor', {
        theme: 'snow',
    });

    quill.on('text-change', function () {
        $('#body-content').val(quill.root.innerHTML);
        console.log(quill.root.innerHTML);
    });

    function insertTag(text)
    {
        tag = ' <<- ' + text + ' ->> ';
        quill.insertText( quill.getSelection().index, tag)
    }

</script>

@endpush

@push('style')

<link href="//cdn.quilljs.com/1.2.6/quill.snow.css" rel="stylesheet">
<link href="//cdn.quilljs.com/1.2.6/quill.bubble.css" rel="stylesheet">
<style>
    .variable {
        margin: 3px;
        line-height: 25px;
    }
</style>
@endpush