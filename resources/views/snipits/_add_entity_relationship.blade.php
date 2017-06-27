@push('modal')
    <div class="modal" id="addEntityRelationship" tabindex="-1" role="dialog" aria-labelledby="addEntityRelationshipLabel" aria-hidden="true">
         <div class="modal-dialog">
    		<div class="modal-content">
                <form action="{{route('entity.addRelationship')}}" class="form-horizontal" method="post">
                {{csrf_field()}}
                <div class="modal-header">
    				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    				<h4 class="modal-title">Create Entity Relationship</h4>
    			</div>
    			<div class="modal-body">
                        @if(isset($property))
                            <input type="hidden" name="property_id" value="{{$property->id}}">
                        @else
                            <div class="form-group">
                                <label for="full_address" class="col-sm-4 control-label">Entity Role</label>
                                <div class="col-sm-8">
                                    <select type="text" name="role" id='role' class="form-control" required>
                                        <option value="">Select One</option>
                                        <option value="Owner">Owner</option>
                                        <option value="Tenant">Tenant</option>
                                        <option value="Manager">Property Manager</option>
                                        <option value="Receiver">Receiver</option>
                                        <option value="Common Name">Common Name</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="properties" class="col-sm-4 control-label">Property</label>
                                <div class="col-sm-8">
                                    <select type="text" name="property_id" id='properties' class="form-control" style="width: 100%">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>

                        @endif

                        @if(isset($entity))
                            <input type="hidden" name="entity_id" value="{{$entity->id}}">
                        @else

                        <div class="form-group">
                            <label for="entities" class="col-sm-4 control-label">Entity</label>
                            <div class="col-sm-8">
                                <select type="text" name="property_id" id='properties' class="form-control" style="width: 100%">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>

                        @endif

                </div>
    			<div class="modal-footer">
    				<button type="button" class="btn btn-default btn-raised" data-dismiss="modal">Close</button>
    				<input type="submit" class="btn btn-primary btn-raised" value="Add Relationship">
    			</div>
                </form>

            </div><!-- /.modal-content -->
    	</div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endpush

@push('scripts')

<script>
    @unless(isset($properties))
    $.get("{{route('properties.index')}}?type=select", function(data) {
        $('#properties').select2({
            data: data
        });
    });
    @endunless

    @unless(isset($entities))
    $.get("{{route('entity.index')}}?type=select", function(data) {
        $('#entities').select2({
            data: data
        });
    });
    @endunless

</script>

@endpush