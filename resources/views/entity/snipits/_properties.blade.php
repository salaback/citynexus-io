@foreach($properties as $property)
    <div class="list-group-item" >
        <a class="col-xs-10" href="{{route('properties.show', [$property->id])}}">
            @if($property->is_building && $property->units->count() > 0)
                <i class="fa fa-building"></i>
            @elseif($property->units->count() == 0)
                <i class="fa fa-home"></i>
            @endif
            {{$property->oneLineAddress}}

            -
            <small>{{$property->pivot->role}}</small>
        </a>
        <a href="{{route('entity.removeRelationship', [$property->pivot->id])}}" class="col-xs-2" title="Unlink Relationship">
            <i class="fa fa-unlink"></i>
        </a>
        <div class="row"></div>
    </div>
@endforeach