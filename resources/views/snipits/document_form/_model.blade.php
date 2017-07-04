<div class="form-group">
    <label class="col-sm-3 control-label">
        Related {{title_case($model)}}
    </label>
    <div class="col-sm-9">
        <select name="{{$model}}_id" id="{{$model}}_models" class="select" style="width: 100%">
            <option value=""></option>
        </select>
    </div>
</div>