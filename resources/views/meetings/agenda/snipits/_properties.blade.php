<div class="list-group">
    @foreach($element['properties'] as $property)
        <div class="list-group-item">
            @include('meetings.agenda.snipits._property')
        </div>
    @endforeach
</div>
