<ul class="list-group">
    @foreach($property->entities as $entity)
        <li class="list-group-item">
            {{$entity->name}} <small>{{$entity->title}}</small>
        </li>
    @endforeach
</ul>

@push('scripts')

@endpush

@push('modal')

@endpush