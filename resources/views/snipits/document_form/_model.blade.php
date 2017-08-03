<div class="form-group">
    <label class="col-sm-3 control-label">
        Related {{title_case($model)}}
    </label>
    <div class="col-sm-8">
        <select name="{{$model}}_id" id="{{$model}}_models" class="select" style="width: 100%">
            <option value=""></option>
        </select>
    </div>
    <div class="col-sm-1">
        <span class="fa fa-plus-circle fa-2x" onclick="addNew('{{$model}}')"></span>
    </div>
</div>