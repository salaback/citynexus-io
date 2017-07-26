<!-- Modal -->
<div class="modal fade" id="tagModal" tabindex="-1" role="dialog" aria-labelledby="tagModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create Tag Score Element</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="col-sm-3 control-label">Select Tag</label>
                    <div class="col-sm-9">
                        <label for="tags"></label>
                        <select tabindex="3" name="tags" class="form-control" id="tags">
                            <option value="">Select One</option>
                            @foreach(\App\PropertyMgr\Model\Tag::orderBy('tag')->get() as $tag)
                                <option value="{{$tag->id}}">{{$tag->tag}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-sm-12 hidden" id="counts">
                        <div class="col-md-12">
                            <label>Trailing Period</label>
                            <select name="dp_trailing" class="form-control" id="tag_trailing">
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
                        <div class='togglebutton'>
                            <label>
                                <input type="checkbox" name="tagged" id="tagged" checked="" value="true">
                                <span id="properties-count"></span> properties tagged</label>
                        </div>
                        <div class="togglebutton">
                            <label>
                                <input type="checkbox" name="tagged_range" id="tagged_range" value="true">
                                Properties within <input type="number" class="range-field" name="tag_range" id="tag_range" value="50"> meters of tagged properties</label>
                        </div>
                        <div class="togglebutton">
                            <label>
                                <input type="checkbox" name="trashed" id="trashed" value="true">
                                <span id="properties-trashed"></span> previously tagged properties</label>
                        </div>
                        <div class="togglebutton">
                            <label>
                                <input type="checkbox" name="trashed_range" id="trashed_range" value="true">
                                Properties within <input type="number" class="range-field" name="trash_range" id="trash_range" value="50"> meters of previously tagged properties</label>
                        </div>
                        <div class="col-sm-12">
                            <strong>Score Effect</strong><br>
                            <div class="form-group">
                                <label>Effect Type</label>
                                <select name="scoreEffect" class="form-control" id="scoreEffect">
                                    <option value="">Select one</option>
                                    <option value="ignore">Ignore Properties</option>
                                    <option value="add">Add to Score</option>
                                    <option value="subtract">Subtract from Score</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group hidden" id="factorWrapper">
                                    <label for="factor"> Amount</label>
                                    <input type="number" name="factor" id="factor" class="form-control" value="1">
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
            <div class="row"></div>

            <div class="model-footer hidden" id="addTagFooter">
                <div class="col-sm-12">
                    <button class="btn btn-primary btn-raised" onclick="addTagElement()">Add Element to Score</button>
                </div>
            </div>
            <div class="row"></div>

        </div>
    </div>
</div>


@push('scripts')

<script>

    $('#scoreEffect').change(function (event) {
       if($('#scoreEffect').val() == 'add' || $('#scoreEffect').val() == 'subtract')
        {
            $('#factorWrapper').removeClass('hidden');
        } else {
            $('#factorWrapper').addClass('hidden');
       }
       if($('#scoreEffect').val() != null)
       {
           $('#addTagFooter').removeClass('hidden');
       } else {
           $('#addTagFooter').addClass('hidden');
       }

    });


    $('#tags').change(function () {
        var tag = $('#tags').val();

        getTags(tag, {});

        $('.tagDate').change(function () {
            var tag = $('#tags').val();
            var to = $('#to').val();
            var from = $('#from').val();
            getTags(tag, {
                to: to,
                from: from
            });
        });

    });

    var getTags = function(tag, options)
    {
        $.ajax({
            url: '{{route('tag.index')}}/' + tag,
            type: 'GET',
            data: options,
            success: function (response) {
                $('#counts').removeClass('hidden');
                $('#properties-count').html(response.properties.tagged);
                $('#entities-count').html(response.entities.tagged);
                $('#properties-trashed').html(response.properties.deleted);
                $('#entities-trashed').html(response.entities.deleted);
                if(response.range.from != null) $('#from').val(response.range.from);
                if(response.range.to != null) $('#to').val(response.range.to);
            }
        })
    }

    var addTagElement = function()
    {
        $('#tagModal').modal('hide');

        var tagged = false;
        var tagged_range = false;
        var trashed = false;
        var trashed_range = false;

        if($('#tagged').prop('checked')) {
            tagged = true;
        }

        if($('#trashed').prop('checked')) {
            trashed = true;
        }

        if($('#tagged_range').prop('checked'))
        {
            tagged_range = $('#tag_range').val();
        }

        if($('#trashed_range').prop('checked'))
        {
            trashed_range = $('#trash_range').val();
        }

        var element = {
            type: 'tag',
            tag_id: $('#tags').val(),
            trailing: $('#tag_trailing').val(),
            effect: {
                type: $('#scoreEffect').val(),
                factor: $('#factor').val()
              },
            tags: {
                tagged: tagged,
                trashed: trashed,
                tagged_range: tagged_range,
                trashed_range: trashed_range
            }
        };

        $('#counts').addClass('hidden');
        $('#addTagFooter').addClass('hidden');
        $('#factorWrapper').addClass('hidden');
        $("#scoreEffect option[value='']").prop('selected', true);
        $("#tags option[value='']").prop('selected', true);

        addElement(element);
    };

</script>
@endpush