
<tr>
    <td>
        <input type="checkbox" name="map[{{$i['key']}}][skip]" >
    </td>

    <td><input type="checkbox" name="map[{{$i['key']}}][show]" checked></td>
    <td><input type="text" id="name-{{$i['key']}}" name="map[{{$i['key']}}][name]" class="form-control" value="{{$i['name']}}"></td>

    <td>
        {{$i['key']}}
        <input type="hidden" name="map[{{$i['key']}}][key]" value="{{$i['key']}}">
    </td>
    <td>
        {{$i['value']}}
    </td>

    <td>
        <select name="map[{{$i['key']}}][type]" class="form-control" id="type-{{$i['key']}}">
            <option value="string">String</option>
            <option value="text" @if($i['type'] == 'text') selected @endif>Text Area</option>
            <option value="integer" @if($i['type']  == 'integer') selected @endif>Integer</option>
            <option value="float" @if($i['type']  == 'float') selected @endif>Float</option>
            <option value="boolean" @if($i['type']  == 'boolean') selected @endif>Boolean</option>
            {{-- TODO: Datetime not working on scheme, need to make it convert when uploading --}}
            <option value="datetime" @if($i['type']== 'datetime') selected @endif>Date Time</option>
        </select>
    </td>
</tr>