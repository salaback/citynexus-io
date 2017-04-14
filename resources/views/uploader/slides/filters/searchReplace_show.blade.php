<input type="hidden" name="filters[{{$filter['key']}}][{{$key}}][type]"  value="searchReplace">
<input type="hidden" name="filters[{{$filter['key']}}][{{$key}}][key]"  value="{{$filter['key']}}">
<input type="hidden" name="filters[{{$filter['key']}}][{{$key}}][needle]"  value="{{$filter['needle']}}">
<input type="hidden" name="filters[{{$filter['key']}}][{{$key}}][replace]"  value="{{$filter['replace']}}">
Search for <i class="label label-default">{{$filter['needle']}}</i> and replace with <i class="label label-default">{{$filter['replace']}}</i>
