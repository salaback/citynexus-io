<div class="form-inline filter-wrapper" id="{{$uid}}">
    <input type="hidden" class="class-filter" name="filter[{{$uid}}][type]" value="searchReplace">
    <input type="hidden" class="class-filter" name="filter[{{$uid}}][key]" value="{{$key}}">
    <div class="form-group">
        <label for="filter[needle]">
            Search for:
        </label>
        <input type="text" class="form-control" name="filter[{{$uid}}][needle]">
    </div>

    <div class="form-group">
        <label for="filter[needle]">
            Replace with:
        </label>
        <input type="text" class="form-control" name="filter[{{$uid}}][replace]">
    </div>
     <i class="fa fa-trash pull-right" style="cursor: pointer" onclick="$('#{{$uid}}').remove();"></i>
</div>
