<!-- Modal -->
<div class="modal" id="datapointModal" tabindex="-1" role="dialog" aria-labelledby="tagModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create Data Point Score Element</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="datasets">Data Set</label>
                                <select tabindex="3" name="tags" class="form-control" id="datasets">
                                    <option value="">Select One</option>
                                    @foreach(\App\DataStore\Model\DataSet::orderBy('name')->get() as $dataset)
                                        <option value="{{$dataset->id}}">{{$dataset->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <div id="datapoint_wrapper" class="hidden">
                                    <label for="datapoint">Datapoint</label>
                                    <select name="datapoint" id="datapointSelect" class="form-control">
                                        <option value="">Select One</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div id="numeric_wrapper" class="hidden datapoint_settings">
                                    <div class="well">
                                        @include('analytics.score.snipits._datapoint_numeric')
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="datapoint_settings_wrapper" class="hidden datapoint_settings">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h4>Select the effected buildings</h4>
                                    <div class='togglebutton'>
                                        <label>
                                            <input type="checkbox" name="datapointProperty" id="datapoint_property" checked="">
                                            <span id="properties-count"></span> properties with data point</label>
                                    </div>
                                    {{--<div class="togglebutton">--}}
                                        {{--<label>--}}
                                            {{--<input type="checkbox" name="datapointPropertyRange" id="datapoint_property_range">--}}
                                            {{--Properties within <input type="number" class="range-field" name="datapoint_property_range_meters" id="datapoint_property_range_meters" value="50"> meters of properties with data point.</label>--}}
                                    {{--</div>--}}
                                </div>
                                <div class="col-sm-6">
                                    <h4>How to treat units</h4>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="datapointTreatUnits" id="datapointTreatUnitsTotal" checked="true">
                                            Total Score for building.</label>
                                    </div>
                                    {{--<div class="radio">--}}
                                        {{--<label>--}}
                                            {{--<input type="radio" name="datapointTreatUnits" id="datapointTreatUnitsAverage">--}}
                                            {{--Total score for building divided by units.</label>--}}
                                    {{--</div>--}}

                                </div>
                            </div>


                            <div class="row">
                                <div class="col-sm-6">
                                    <h4>Choose date range</h4>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="datapointMostRecent" id="recentRecent" value="recent" checked="true">
                                            Use most recent data point from building.</label>
                                    </div>
                                    {{--<div class="radio">--}}

                                        {{--<label>--}}
                                            {{--<input type="radio" name="datapointMostRecent" value="recentUnit">--}}
                                            {{--Use most recent data point for each unit.</label>--}}
                                    {{--</div>--}}
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="datapointMostRecent" id="recentAll" value="all">
                                            Use all data points.</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Trailing Period</label>
                                            <select name="dp_trailing" class="form-control" id="dp_trailing">
                                                <option value="">Select One</option>
                                                <option value="1">One day</option>
                                                <option value="7">One week</option>
                                                <option value="14">Two week</option>
                                                <option value="30">30 days</option>
                                                <option value="90">90 days</option>
                                                <option value="180">180 days</option>
                                                <option value="365">One year</option>
                                                <option value="730">Two years</option>
                                                <option value="1095">Three years</option>
                                                <option value="1460">Four years</option>
                                                <option value="1825">Five years</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            <div class="col-sm-12">
                                <h4>Score Effect</h4>
                                <div class="row" id="stringEffect">
                                    <div class="col-sm-6">
                                        <label for="">String Test</label>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <select name="scoreEffect" class="form-control" id="stringTestType">
                                                    <option value="">Select one</option>
                                                    <option value="contains">Contains</option>
                                                    <option value="notcontains">Doesn't Contains</option>
                                                    <option value="matches">Matches Exactly</option>
                                                    <option value="notmatches">Doesn't Exactly Match</option>
                                                    <option value="blank">Is Blank</option>
                                                    <option value="notblank">Is Not Blank</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" id="stringTest" class="form-control" placeholder="Query...">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="col-sm-6">
                                            <label>Then add to score:</label>
                                            <input type="number" id="stringTestEffect" onchange="$('#addDatapointFooter').removeClass('hidden');" name="add_to_score" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="numericEffect">
                                    <div class="col-sm-6">
                                        <label>Effect Type</label>
                                        <select name="scoreEffect" class="form-control" id="datapointScoreEffect">
                                            <option value="">Select one</option>
                                            <option value="value">Value of Data Point</option>
                                            <option value="log">Log of value</option>
                                            <option value="square">Square of value</option>
                                            <option value="cube">Cube of value</option>
                                            <option value="root">Square root of value</option>
                                            <option value="root">Cube root of value</option>
                                            <option value="index">Indexed Score</option>
                                            <option value="zscore">Z-score</option>
                                            <option value="range">Range Test</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="datapointAddOrSubtract" value="add" checked="true">
                                                Add to the score.</label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="datapointAddOrSubtract" value="subtract">
                                                Subtract from the score.</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row hidden effect_settings" id="datapointRangeTest">
                            <div class="col-sm-12">
                                <h4>Range Test</h4>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class='togglebutton'>
                                            <label>
                                                <input type="checkbox" name="datapointProperty" id="datapoint_greater_than" checked="" value="true">
                                                Data point is greater than <input type="number" name="datapoint_greater_than_test" id="datapoint_greater_than_test" value=""></label>
                                        </div>
                                        <div class="togglebutton">
                                            <label>
                                                <input type="checkbox" name="datapointPropertyRange" id="datapoint_less_than" value="true">
                                                Data point is less than <input type="number" name="datapoint_less_than" id="datapoint_less_than_test" value=""></label>
                                        </div>
                                        <div class="togglebutton">
                                            <label>
                                                <input type="checkbox" name="datapointPropertyRange" id="datapoint_equal_to" value="true">
                                                Data point is equal to <input type="number" name="datapoint_equal_to" id="datapoint_equal_to_test" value=""></label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Then add to score:</label>
                                        <input type="number" id="datapoint_add_to_score" name="add_to_score" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>

                </div>
            <div class="model-footer hidden" id="addDatapointFooter">
                <div class="row">
                    <div class="col-sm-12">
                        <button class="btn btn-primary btn-raised" onclick="addDatapointElement()">Add Element to Score</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>



@push('style')

@endpush
@push('scripts')
<script>

    var dataType;

    $("#datapointScoreEffect").change(function(e) {
        var effect = $('#datapointScoreEffect').val();
        if(effect == 'range')
        {
            $('#datapointRangeTest').removeClass('hidden');
        }
        else if (effect != 'range')
        {
            $('#datapointRangeTest').addClass('hidden');
        }

        if(effect == '')
        {
            $('#addDatapointFooter').addClass('hidden');
        }
        else if (effect != '')
        {
            $('#addDatapointFooter').removeClass('hidden');
        }
    });
    $('#datasets').change(function(e){
        $.get("{{route('dataset.index')}}/" + $('#datasets').val(), function(data) {
            $('#datapointSelect').html('<option>Select One</option>');
            for (var item in data.schema) {
                $('#datapointSelect').append('<option value="' + data.schema[item]['key'] + '">' + data.schema[item]['name'] + ' [' +data.schema[item]['type'] + ']</option>');
            }
            $('#datapoint_wrapper').removeClass('hidden');
        });
    });

    $('#datapointSelect').change(function (e) {
        var datapoint = $('#datapointSelect');

        if(datapoint.val() != null)
        {
            $.ajax({
                url: '{{ route('dataset.datapointInfo') }}/' + $('#datasets').val() + '/' + datapoint.val(),
                type: 'GET',
                success: function(data) {
                    $('#addDatapointFooter').addClass('hidden');

                    if(data.type == 'integer' || data == 'float')
                    {
                        dataType = 'numeric';
                        numericDatapoint(data);
                        $('#numericEffect').removeClass('hidden');
                        $('#stringEffect').addClass('hidden');
                    }
                    else {
                        dataType = 'string';
                        $('#datapoint_settings').addClass('hidden');
                        $('#stringEffect').removeClass('hidden');
                        $('#numericEffect').addClass('hidden');
                    }

                    $('#datapoint_settings_wrapper').removeClass('hidden');
                },
                error: function (data) {
                    alert('warning', "Uh oh. Something went wrong.");
                }
            })
        }

    });

    var addDatapointElement = function()
    {

        $('#datapointModal').modal('hide');


        var units = 'total';
        var propertyRange = false;
        var property = false;
        var greaterThan = false;
        var lessThan = false;
        var equalTo = false;
        var recent = 'recent';

        if($('#datapoint_property').prop('checked')) property = true;
        if($('#recentAll').prop('checked')) recent = 'all';
        if($('#datapoint_property_range').prop('checked')) propertyRange = $('#datapoint_property_range_meters').val();
        if($('#datapointTreatUnitsAverage').prop('checked')) units = 'average';
        if($('#datapoint_greater_than').prop('checked')) greaterThan = $('#datapoint_greater_than_test').val();
        if($('#datapoint_less_than').prop('checked')) lessThan = $('#datapoint_less_than_test').val();
        if($('#datapoint_equal_to').prop('checked')) equalTo = $('#datapoint_equal_to_test').val();

        var effect;
        if(dataType == 'numeric')
        {

            effect = {
                type: $('#datapointScoreEffect').val(),
                effect: $('input[name="datapointAddOrSubtract"]:checked').val(),
                range: {
                    greaterThan: greaterThan,
                    lessThan: lessThan,
                    equalTo: equalTo,
                    add: $('#datapoint_add_to_score').val()
                }
            };
        }
        else if(dataType == 'string')
        {
            effect = {
                type: 'string',
                method: $('#stringTestType').val(),
                test: $('#stringTest').val(),
                effect: $('#stringTestEffect').val(),
            };
        }

        var element = {
            type: 'datapoint',
            dataset_id: $('#datasets').val(),
            key: $('#datapointSelect').val(),
            recent: recent,
            properties: {
                units: units,
                property: property,
                propertyRange: propertyRange
            },
            trailing: $('#dp_trailing').val(),
            effect: effect
        };

        addElement(element);
    }

</script>
@endpush