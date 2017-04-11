<h3>Create New Uploader</h3>


<div class="form-horizontal">
    <div class="form-group">
        <label for="name" class="col-sm-2">
            Uploader Name
        </label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="name" id="upload_name">
        </div>
    </div>

    <div class="form-group">
        <label for="description" class="col-sm-2">
            Description
        </label>
        <div class="col-sm-10">
            <textarea name="description" class="form-control" id="upload_description" cols="30" rows="5"></textarea>
        </div>
    </div>
</div>

<h3>Select an Upload Type</h3>
{{-- Start CSV/Excel Upload Tile --}}
<div class="option-tile col-sm-4 upload_type" id="upload">
    <div class="option-wrapper">
        <div class="option-header">
            Create CSV/Excel Uploader
        </div>
        <div class="option-icon">
            <i class="fa fa-upload fa-5x"></i>
        </div>
    </div>
</div>

{{-- Start Dropbox Upload Tile --}}
<div class="option-tile col-sm-4 upload_type hidden" id="dropbox">
    <div class="option-wrapper">
        <div class="option-header">
            Create Dropbox Uploader
        </div>
        <div class="option-icon">
            <i class="fa fa-dropbox fa-5x"></i>
        </div>
    </div>
</div>

{{-- Start Entry Form Upload Tile --}}
<div class="option-tile col-sm-4 upload_type hidden" id="webform">
    <div class="option-wrapper">
        <div class="option-header">
            Create Web Form
        </div>
        <div class="option-icon">
            <i class="fa fa-file-text-o fa-5x"></i>
        </div>
    </div>
</div>

{{-- Start CSV Upload Tile --}}
<div class="option-tile col-sm-4 upload_type hidden" id="google_sheet">
    <div class="option-wrapper">
        <div class="option-header">
            Create Google Spreadsheet Uploader
        </div>
        <div class="option-icon">
            <i class="fa fa-google fa-5x"></i>
        </div>
    </div>
</div>

{{-- Start API Uploader Tile --}}
<div class="option-tile col-sm-4 upload_type hidden" id="api">
    <div class="option-wrapper">
        <div class="option-header">
            Create API Uploader
        </div>
        <div class="option-icon">
            <i class="fa fa-code-fork fa-5x"></i>
        </div>
    </div>
</div>

<div class="btn btn-primary" id="create_uploader">Create Uploader</div>