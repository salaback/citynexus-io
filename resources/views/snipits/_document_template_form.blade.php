<form action="{{route('documents.store')}}" class="form-horizontal" method="post">
    {{csrf_field()}}
    <input type="hidden" name="template_id" value="{{$template->id}}">
    @foreach($data as $key => $item)

        @if($key == 'unit')
                @include('snipits.document_form._unit')
            @else
            @unless($key == 'building' && isset($data['unit']))
                @include('snipits.document_form._model', ['model' => $key])
            @endunless
        @endif

    @endforeach
    <input type="submit" value="Preview Letter" class="btn btn-primary btn-raised">
</form>

<script>
    var building_data;
    var entity_data;
    var sender_data;
    var loadDocumentSelectFields = function() {
        @if(isset($data['building']) && !isset($data['unit']))
             building_data = {!! json_encode($building_data) !!};
            $('#building_models').select2({
                placeholder: "Select a building...",
                data: building_data
            });
            @if(isset($property->building)) $("#building_models").val({{$property->building->id}}).trigger('change'); @endif

        @endif

        @if(isset($data['unit']))
        building_data = {!! json_encode($building_data) !!};
        $('#building_models').select2({
                placeholder: "Select a building...",
                data: building_data
            });
        $('#building_models').change(function () {
           $.ajax({
               url: "{{route('property.getUnits')}}/" + $('#building_models').val(),
               success: function(data)
               {
                   $('#unit_models').select2({
                       placeholder: "Select a unit...",
                       data: data
                   });
                   @if(isset($property) && $property->is_unit)
                     $("#unit_models").val({{$property->id}}).trigger('change');
                   @endif

                   $('#unit_wrapper').removeClass('hidden');
               }
           });
        });
        @if(isset($property) && $property->is_unit)
                $("#building_models").val({{$property->building->id}}).trigger('change');
        @elseif(isset($property) && $property->is_building)
                $("#building_models").val({{$property->id}}).trigger('change');
        @endif

                @endif

        @if(isset($data['entity']))
            entity_data = {!! json_encode($entity_data) !!};
            $('#entity_models').select2({
                placeholder: "Select an entity...",
                data: entity_data
            });

         @if(isset($entity)) $("#entity_models").val({{$entity->id}}).trigger('change'); @endif

        @endif

        @if(isset($data['sender']))
        sender_data = {!! json_encode($sender_data) !!};
        $('#sender_models').select2({
            placeholder: "Select a sender...",
            data: sender_data
        });
        $("#sender_models").val({{\Illuminate\Support\Facades\Auth::id()}}).trigger('change');
        @endif
    }
</script>
