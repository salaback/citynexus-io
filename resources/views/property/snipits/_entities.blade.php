

<ul class="list-group">
        <a role="button" class="list-group-item" data-toggle="modal" data-target="#addEntityRelationship">
                <i class="fa fa-plus"> </i> Link to Related Property
        </a>
    @foreach($property->entities as $entity)
        <a class="list-group-item" href="{{route('entity.show', [$entity->id])}}">
            {{$entity->name}} <small>({{$entity->pivot->role}})</small>
        </a>
    @endforeach
   @php
        unset($entity);
   @endphp
</ul>

@include('snipits._add_entity_relationship')

@push('scripts')

@endpush

@push('modal')

@endpush