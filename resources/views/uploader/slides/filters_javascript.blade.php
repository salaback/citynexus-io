<script>

    var upload_id = null;
    var uploader_id = {{$uploader->id}};
    // Get filters
    var getFilters = function (data_type, key) {
        // get ajax filter
        $.ajax({
            url: '{{action('UploaderController@post')}}',
            type: 'POST',
            data: {
                _token: "{{csrf_token()}}",
                slug: 'get_filters',
                data_type: data_type
            }

        }).success(function (data) {
            // on success
            // trigger model with results
            triggerModal('Add Filter to ' + key, data);

            // add key ID to filter form
            filter_key = key;
        });


    };

    //
    var addFilter = function (filter) {
        // get filter line
        $.ajax({
            url: '{{action('UploaderController@post')}}',
            type: 'POST',
            data: {
                _token: '{{csrf_token()}}',
                slug: 'add_filter',
                filter: filter,
                key: filter_key
            }
        }).success(function (data) {
                    $('#filters-queue').append(data);
                    $('#button-row').removeClass('hidden');
                }
        );
    };

    var saveFilters = function (id) {
        var inputs = $('#filter-settings');
        var key = $('filter_key').val();
        $.ajax({
            url: '{{action('UploaderController@post')}}?' + inputs.serialize(),
            type: "POST",
            data: {
                slug: 'save_filter',
                _token: "{{csrf_token()}}"
            }
        }).success(function (data) {
            $("#filters-" + filter_key).append(data);
            Custombox.close('#modal');
        });
    };

    var commitFilters = function () {
        var form = $('#field-filters');
        $.ajax({
            url: '{{action('UploaderController@post')}}',
            type: "POST",
            data: {
                slug: 'commit_filter',
                uploader_id: uploader_id,
                form: form.serializeArray(),
                _token: "{{csrf_token()}}"
            }
        }).success(function(data){
            setTimeout(function(){
                getSlide('map');
            }, 3000);
        });
    };

</script>