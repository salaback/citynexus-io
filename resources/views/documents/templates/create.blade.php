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
                <input type="hidden" id="body" name="body">
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
                                <input type="checkbox" name="relations[buildings]" id="buildings" value="true">
                                Buildings</label>
                        </div>
                        <div class='togglebutton'>
                            <label>
                                <input type="checkbox" name="relations[units]" id="units" value="true">
                                Units</label>
                        </div>
                        <div class='togglebutton'>
                            <label>
                                <input type="checkbox" name="relations[entities]" id="entities" value="true">
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
                    <div id="building-variables">
                        <h4>Building Variables</h4>
                        <div class="variables">
                            <div class="label label-default variable" draggable="true" onclick="insertTag('building:address')"> Address </div>
                            <div class="label label-default variable" draggable="true" onclick="insertTag('building:units')"> Units </div>
                            <div class="label label-default variable" draggable="true" onclick="insertTag('building:city')"> City </div>
                            <div class="label label-default variable" draggable="true" onclick="insertTag('building:state')"> State </div>
                            <div class="label label-default variable" draggable="true" onclick="insertTag('building:postcode')"> Postal Code </div>

                        </div>
                    </div>
                    <div id="unit-variables">
                        <h4>Unit Variables</h4>
                        <div class="variables">
                            <div class="label label-default variable" draggable="true" onclick="insertTag('unit:address')"> Address </div>
                            <div class="label label-default variable" draggable="true" onclick="insertTag('unit:unit')"> Unit Number </div>
                        </div>
                    </div>
                    <div id="entity-variables">
                        <h4>Entity Variables</h4>
                        <div class="variables">
                            <div class="label label-default variable" draggable="true" onclick="insertTag('entity:first_name')"> First Name </div>
                            <div class="label label-default variable" draggable="true" onclick="insertTag('entity:last_name')"> Last Name </div>
                            <div class="label label-default variable" draggable="true" onclick="insertTag('entity:full_name')"> Full Name </div>
                            <div class="label label-default variable" draggable="true" onclick="insertTag('entity:mailing_address')"> Mailing Address </div>
                        </div>
                    </div>
                    <div id="sender-variables">
                        <h4>Sender Variables</h4>
                        <div class="variables">
                            <div class="label label-default variable" draggable="true" onclick="insertTag('sender:first_name')"> First Name </div>
                            <div class="label label-default variable" draggable="true" onclick="insertTag('sender:last_name')"> Last Name </div>
                            <div class="label label-default variable" draggable="true" onclick="insertTag('sender:full_name')"> Full Name </div>
                            <div class="label label-default variable" draggable="true" onclick="insertTag('sender:title')"> Title </div>
                            <div class="label label-default variable" draggable="true" onclick="insertTag('sender:department')"> Department </div>
                            <div class="label label-default variable" draggable="true" onclick="insertTag('sender:email')"> Email </div>
                        </div>
                    </div>
                    <div id="misc-variables">
                        <h4>Misc. Variables</h4>
                        <div class="variables">
                            <div class="label label-default variable" draggable="true" onclick="insertTag('misc:document_id')"> Document ID </div>
                            <div class="label label-default variable" draggable="true" onclick="insertTag('misc:queue_id')"> Queue ID </div>
                            <div class="label label-default variable" draggable="true" onclick="insertTag('misc:printed_at')"> Time Stamp when Printed </div>
                            <div class="label label-default variable" draggable="true" onclick="insertTag('misc:created_at')"> Time Stamp when Queued </div>
                        </div>
                    </div>
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
                        <p><<- entity:last_name ->></p>
                        <p><<- entity:mailing_address ->></p>
                        <p><br></p>
                        <p><<- entity:last_name ->>;</p>
                        <p><br></p>
                        <p>This letter is to inform you of an issue in unit  <<- unit ->> of your building at  <<- building:address ->>.</p>
                        <p><br></p>
                        <p>Please contact me at your earliest convenience.</p>
                        <p><br></p>
                        <p>Regards,</p>
                        <p><br></p>
                        <p><<- sender:last_name ->></p>
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
        $('#body').val(quill.root.innerHTML);
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