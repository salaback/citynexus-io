<div class="form-group">
    <label class="col-sm-3 control-label">
        Related Building
    </label>
    <div class="col-sm-8">
        <select name="building" id="building_models" style="width: 100%">
            <option value=""></option>
        </select>
    </div>
    <div class="col-sm-1">
        <span class="fa fa-plus-circle fa-2x" onclick="addNew('building')"></span>
    </div>
</div>

<div class="form-group hidden" id="unit_wrapper">
    <label class="col-sm-3 control-label">
        Related Unit
    </label>
    <div class="col-sm-8 control-label">
        <select name="property_id" id="unit_models" style="width: 100%">
            <option value=""></option>
        </select>
    </div>
    <div class="col-sm-1">
        <span class="fa fa-plus-circle fa-2x" onclick="addNew('unit')"></span>
    </div>
</div>