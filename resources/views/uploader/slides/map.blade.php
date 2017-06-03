
<tr>
    <td>
        <input type="checkbox" name="map[{{$i['key']}}][skip]" @if(isset($schema[$key]) && isset($schema[$key]['skip'])) checked @endif >
    </td>

    <td><input type="checkbox" name="map[{{$i['key']}}][show]" @if(isset($schema[$key]) && isset($schema[$key]['show'])) checked @elseif(!isset($schema[$key])) checked @endif></td>
    <td><input type="text" id="name-{{$i['key']}}" name="map[{{$i['key']}}][name]" class="form-control"
               value="@if(isset($schema[$key]) && isset($schema[$key]['name'])) {{$schema[$key]['name']}} @else {{$i['name']}} @endif"></td>
    <td>
        {{$key}}
    </td>
    <td>
        @if(isset($schema[$key]) && isset($schema[$key]['key']))
            <select name="map[{{$key}}][key] mapsto" id="mapsto-{{$key}}">
                <option value="create">Create New Field</option>
                <option value="ignore">Ignore</option>
            @foreach($schema as $item)
                    <option value="{{$item['key']}}" @if($item['key'] == $key) selected @endif>{{$item['name']}}</option>
                @endforeach
            </select>
        @else
            {{$i['key']}}
            <input type="hidden" name="map[{{$i['key']}}][key]" value="{{$i['key']}}">
        @endif
    </td>
    <td>
        @if(isset($schema[$key]) && isset($schema[$key]['key']))
            <input type="text" name="map[{{$i['key']}}][newKey]" value="" id="newkey-{{$key}}">
        @else
            <input type="text" name="map[{{$i['key']}}][newKey]" value="{{$i['key']}}" id="newkey-{{$key}}">
        @endif
    </td>
    <td>
        {{$i['value']}}
    </td>

    <td>
        @if(isset($schema[$key]) && isset($schema[$key]['type']))
            {{$schema[$key]['type']}}
        @else
            <select name="map[{{$i['key']}}][type]" class="form-control" id="type-{{$i['key']}}">
                <option value="string">String</option>
                <option value="text" @if($i['type'] == 'text') selected @endif>Text Area</option>
                <option value="integer" @if($i['type']  == 'integer') selected @endif>Integer</option>
                <option value="float" @if($i['type']  == 'float') selected @endif>Float</option>
                <option value="boolean" @if($i['type']  == 'boolean') selected @endif>Boolean</option>
                {{-- TODO: Datetime not working on scheme, need to make it convert when uploading --}}
                <option value="datetime" @if($i['type']== 'datetime') selected @endif>Date Time</option>
            </select>
        @endif
    </td>
</tr>

<script>
    $('.mapsto').change(function(action){

    });
</script>