<div class="col-sm-6">
    @include('snipits._add_tags', ['model' => 'App\\\PropertyMgr\\\Model\\\Property', 'model_id' => $property->id])
</div>
<div class="col-sm-6">
    @include('snipits._issue_document', ['property_id' => $property->id])
</div>
<div class="row"></div>