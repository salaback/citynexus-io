<div class="boxs">
    <div class="boxs-header">
        <h1 class="custom-font">Add Tag</h1>
    </div>
    <div class="boxs-body">
        <div id="new-tag-input">
            <input class="typeahead" type="text" id="new-tag" placeholder="Add new tag">
        </div>
    </div>
</div>


@push('scripts')

<script>
            {{--add tags--}}
    var substringMatcher = function(strs) {
                return function findMatches(q, cb) {
                    var matches, substringRegex;

                    // an array that will be populated with substring matches
                    matches = [];

                    // regex used to determine if a string contains the substring `q`
                    substrRegex = new RegExp(q, 'i');

                    // iterate through the pool of strings and for any string that
                    // contains the substring `q`, add it to the `matches` array
                    $.each(strs, function(i, str) {
                        if (substrRegex.test(str)) {
                            matches.push(str);
                        }
                    });

                    cb(matches);
                };
            };

    var tags = {!! json_encode(\App\PropertyMgr\Model\Tag::pluck('tag')) !!};

    $('#new-tag-input .typeahead').typeahead({
                hint: true,
                highlight: true,
                minLength: 1
            },
            {
                name: 'states',
                source: substringMatcher(tags)
            });

    $("#new-tag").bind("keypress", {}, addTag);
    function addTag(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) { //Enter keycode
            e.preventDefault();

            var tag = $('#new-tag').val();
            $('#new-tag').val('');
            $('#no-tags').addClass('hidden');
            $('#pending').removeClass('hidden');
            $.ajax({
                url: "{{route('backend.tag.attach')}}",
                type: 'post',
                data: {
                    _token: "{{csrf_token()}}",
                    tagable_id: {{$model_id}},
                    tagable_type: "{!! $model !!}",
                    tag: tag
                }
            }).success( function( data ) {
                        $("#pending").addClass('hidden');
                        $('#new-tag-input').val(null);
                        $('#tags').append(data);
                    }
            );
        }
    };


    {{--Delete Tag--}}

    function removeTag(id, tag_id)
    {
        console.log(id);
        $('#tag-' + tag_id).addClass('hidden');
        $.ajax({
            url: "{{route('backend.tag.detach')}}",
            type: "post",
            data: {
                _token: "{{csrf_token()}}",
                tagable_id: id
            }
        })
    }
</script>

@endpush