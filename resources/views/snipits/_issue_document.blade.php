<div class="boxs">
    <div class="boxs-header">
        <h1 class="custom-font">Issue a Document</h1>
    </div>
    <div class="boxs-body">
        <select name="issue_document" id="issue_document" class="form-control">
            <option value="">Select One</option>
            @foreach($templates as $template)
                <option value="{{$template->id}}">{{$template->id}}</option>
            @endforeach
        </select>
    </div>
</div>
@push ('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css">
@endpush
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js" integrity="sha256-+mWd/G69S4qtgPowSELIeVAv7+FuL871WXaolgXnrwQ=" crossorigin="anonymous"></script>
<script>


    $('#issue_document').change(function(){
        var doc = $('#issue_document').val();
        if(doc != "")
        {
            $.ajax({
                url: "{{route('templates.getForm')}}/" + doc,
                type: 'POST',
                data: {
                    _token: "{{csrf_token()}}",
                    property_id: {{(isset($property)) ? $property->id : 'null'}},
                    entity_id: {{(isset($entity)) ? $entity->id : 'null'}},
                },
                success: function(data) {
                    $('#document_header').html('Verify Form Letter Data Sources');
                    $('#document_body').html(data);
                    loadDocumentSelectFields();
                    $('#documentModal').modal('show');
                },
                error: function (){
                    alert('warning', "Uh oh. Something went wrong.");
                }
            })
        }

    });
</script>
@endpush

@push('modal')

<div class="modal" id="documentModal" tabindex="-1" role="dialog" aria-labelledby="documentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="document_header"></h4>
            </div>
            <div class="modal-body" id="document_body">
            </div>
        </div>
    </div>
</div>

@endpush