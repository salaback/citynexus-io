<div class="modal" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="tagModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create Comment Score Element</h4>
            </div>
            <div class="modal-body">
                <div id="options">
                    <div class="row">
                        <div class="col-sm-6">
                            <select name="include" class="form-control" id="commentMethod">
                                <option value="include">Comments which include</option>
                                <option value="dontInclude">Comments which don't include</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="commentQuery">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <h4>Select the effected buildings</h4>
                            <div class="form-group">
                                <div class='togglebutton'>
                                    <label>
                                        <input type="checkbox" name="commentProperty" id="comment_property" checked="">
                                        <span id="properties-count"></span> properties with comment</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="togglebutton">
                                    <label>
                                        <input type="checkbox" name="commentPropertyRange" id="comment_property_range">
                                        Properties within <input type="number" name="comment_property_range_meters" id="comment_property_range_meters" value="50" class="range-field"> meters of property with comment.</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <h4>How to treat units</h4>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="commentTreatUnits" value="units" checked="true">
                                    Total Score for building.</label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="commentTreatUnits" value="average">
                                    Total score for building divided by units.</label>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <h4>Search within date range</h4>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>From</label>
                                    <input type="text" id="commentFrom" name="from" class="form-control datapointDate">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>To</label>
                                    <input type="text" id="commentTo" name="to" class="form-control datapointDate" >
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <h4 >Score Effect</h4>
                            <div class="form-group">
                                <label>Effect Type</label>
                                <select name="scoreEffect" class="form-control" id="commentScoreEffect">
                                    <option value="">Select one</option>
                                    <option value="ignore">Ignore Properties</option>
                                    <option value="add">Add to Score</option>
                                    <option value="subtract">Subtract from Score</option>
                                </select>
                            </div>
                            <div class="col-md-12 hidden" id="commentFactorWrapper">
                                <div class="col-sm-6">
                                    <div class="form-group" >
                                        <label for="factor"> Amount</label>
                                        <input type="number" name="factor" id="commentFactor" class="form-control" value="1">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="commentScope" value="unit" checked="true">
                                                Score once per unit</label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="commentScope" value="building">
                                                Score once per building</label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="commentScope" value="all">
                                                Score for every matching comment</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="model-footer hidden" id="addCommentFooter">
                    <div class="row">
                        <div class="col-sm-12">
                            <button class="btn btn-primary btn-raised" onclick="addCommentElement()">Add Element to Score</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')

<script>

    $('#commentScoreEffect').change(function () {
        $('#addCommentFooter').removeClass('hidden');

        if($('#commentScoreEffect').val() == 'add' || $('#commentScoreEffect').val() == 'subtract')
        {
            $('#commentFactorWrapper').removeClass('hidden');
        }
        else
        {
            $('#commentFactorWrapper').addClass('hidden');
        }
    });

    function addCommentElement()
    {
        var property = false;
        var propertyRange = false;

        if($('#comment_property').prop('checked')) property = true;
        if($('#comment_property_range').prop('checked')) propertyRange = $('#comment_property_range_meters').val();

        var element = {
            type: 'comment',
            method: $('#commentMethod').val(),
            query: $('#commentQuery').val(),
            properties: {
                properties: property,
                propertiesRange: propertyRange
            },
            buildings: $('input[name="commentTreatUnits"]:checked').val(),
            timeRange: {
                to: $('#commentTo').val(),
                from: $('#commentFrom').val()
            },
            effect: {
                type: $('#commentScoreEffect').val(),
                factor: $('#commentFactor').val(),
                scope: $('input[name="commentScope"]:checked').val(),
            }
        };

        addElement(element);

        $('#commentModal').modal('hide');
    }

</script>
@endpush