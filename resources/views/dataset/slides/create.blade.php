<div class="row">
    <h3>Create a Data Set</h3>
    <div class="form-horizontal">
        <div class="form-group">
            <label for="name" class="col-sm-2">
                Data Set Name
            </label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="name" id="dataset_name">
            </div>
        </div>

        <div class="form-group">
            <label for="description" class="col-sm-2">
                Description
            </label>
            <div class="col-sm-10">
                <textarea name="description" class="form-control" id="dataset_description" cols="30" rows="5"></textarea>
            </div>
        </div>
    </div>


    <h3>What Type of Data Set?</h3>
    {{-- Start Primary Record Tile --}}
    <div class="option-tile col-sm-4 data_type" onclick="createDataSet('profile')">
        <div class="option-wrapper">
            <div class="option-header">
                Profile Record
            </div>
            <div class="option-icon">
                <i class="fa fa-file-text-o fa-3x"></i><br>
                Records like department record where there is only one record per property, but the time
                series information may be important.
            </div>
        </div>
    </div>

    {{-- Start Fixed Record Tile --}}
    <div class="option-tile col-sm-4 data_type"  onclick="createDataSet('fixed')">
        <div class="option-wrapper">
            <div class="option-header">
                Fixed Record
            </div>
            <div class="option-icon">
                <i class="fa fa-sticky-note-o fa-3x"></i><br>
                Records like an emergency services call for service which once created won't be changed.
            </div>
        </div>
    </div>

    {{-- Start Updating Record --}}
    <div class="option-tile col-sm-4 data_type" onclick="createDataSet('updating')">
        <div class="option-wrapper">
            <div class="option-header">
                Updating Record
            </div>
            <div class="option-icon">
                <i class="fa fa-copy fa-3x"></i><br>
                A record like a building permit or citation, which might have updates to some fields.
            </div>
        </div>
    </div>
</div>

<script>

</script>