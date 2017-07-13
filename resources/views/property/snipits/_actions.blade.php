<div class="row">
    <div class="col-sm-6">
        @include('snipits._add_tags', ['model' => 'App\\\PropertyMgr\\\Model\\\Property', 'model_id' => $property->id])
    </div>
    <div class="col-sm-6">
        @include('snipits._issue_document', ['property_id' => $property->id])
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="boxs">
            <div class="boxs-header">
                <h1 class="custom-font">Merge Property</h1>
            </div>
            <div class="boxs-body">
                <div class="col-sm-8">
                    <input type="text" class='form-control' placeholder="Search Addresses..." id="mergeSearch">
                </div>
                <div class="col-sm-4">
                    <button class="btn btn-primary btn-raised" onclick="mergeSearch($('#mergeSearch').val())">
                        Search
                    </button>
                </div>
                <form action="{{route('properties.merge')}}" method="post">
                    {{csrf_field()}}
                    <input type="hidden" name="primary" value="{{$property->id}}">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="list-group" id="mergeResults" style="height: 250px; overflow: scroll;">
                            </div>
                            <input type="submit" class="btn btn-primary btn-raised" value="Merge Properties">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row"></div>

@push('scripts')
<script>

    $('#mergeSearch').on('')

    var mergeSearch = function (string) {
        $.ajax({
            url: "{{route('properties.mergeSearch', [$property->id])}}/" + string,
            success: function(data) {
                $('#mergeResults').html(data);
            }
        })
    }
</script>
@endpush