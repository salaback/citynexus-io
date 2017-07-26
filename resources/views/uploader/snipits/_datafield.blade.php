<tr>
    <td><input type="checkbox" name="map[{{$i['key']}}][show]" checked></td>
    <td><input type="text" id="name-{{$i['key']}}" name="map[{{$i['key']}}][name]" value="{{$i['name']}}"></td>
    <td>
        <select class="mapto" data-key="{{$i['key']}}" name="map[{{$i['key']}}][key]">
            <option value="create">Create New Field</option>
            <option value="__ignore" class="select-hr">Ignore</option>
            <option value="">------</option>
            @if($schema != null)
            @foreach($schema as $field)
                <option value="{{$field['key']}}" @if($i['key'] == $field['key']) selected @endif>{{$field['name']}}</option>
            @endforeach
            @endif
        </select>
        <span id="mapto-wrapper-{{$i['key']}}" @if(isset($schema[$i['key']])) class="hidden" @endif>
            <input type="text" class="form-control map-to-new" @unless(isset($schema[$i['key']])) value="{{$i['key']}}" @endunless id="mapto-new-{{$i['key']}}" placeholder="New field name">
        </span>
        <input type="hidden" name="map[{{$i['key']}}][key]" id="mapto-{{$i['key']}}" @if(isset($i['key'])) value="{{$i['key']}}" @endif>
        <input type="hidden" name="new_fields[]" id="is-new-{{$i['key']}}" @unless(isset($schema[$i['key']])) value="{{$i['key']}}" @endunless >
    </td>


    <td>
        {{$i['value']}}
    </td>

    <td>
        @if(isset($schema[$i['key']]['type']))
        <div class="label label-default" id="default-type-{{$i['key']}}">{{$schema[$i['key']]['type']}}</div>
        @endif
        <select name="map[{{$i['key']}}][type]"  id="type-{{$i['key']}}" @if(isset($schema[$i['key']]['type'])) class="hidden" @endif>
            <option value="string">String</option>
            <option value="text" @if($i['type'] == 'text') selected @endif>Text Area</option>
            <option value="integer" @if($i['type']  == 'integer') selected @endif>Integer</option>
            <option value="float" @if($i['type']  == 'float') selected @endif>Float</option>
            <option value="boolean" @if($i['type']  == 'boolean') selected @endif>Boolean</option>
             TODO: Datetime not working on scheme, need to make it convert when uploading
            <option value="datetime" @if($i['type']== 'datetime') selected @endif>Date Time</option>
        </select>
    </td>
