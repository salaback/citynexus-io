@forelse($properties as $property)
    <div class="list-group-item">
        <label>
            <input type="checkbox" name="secondary[]" value="{{$property->id}}">
            {{$property->oneLineAddress}}
        </label>
    </div>
@empty
    <div class="list-group-item">
        Sorry, no matching results.
    </div>
@endforelse