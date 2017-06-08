<ul class="list-group">
    @foreach($property->entities as $entity)
        <a class="list-group-item" href="{{route('entity.show', [$entity->id])}}">
            {{$entity->name}} <small>({{$entity->pivot->role}})</small>
        </a>
    @endforeach
</ul>

@push('scripts')

@endpush

@push('modal')

@endpush