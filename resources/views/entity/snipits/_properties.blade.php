@foreach($properties as $property)
    <a class="list-group-item" href="{{route('properties.show', [$property->id])}}">
        @if($property->is_building && $property->units->count() > 0)
            <i class="fa fa-building"></i>
        @elseif($property->units->count() == 0)
            <i class="fa fa-home"></i>
        @endif
        {{$property->oneLineAddress}}

        -
        <small>{{$property->pivot->role}}</small>
    </a>
@endforeach