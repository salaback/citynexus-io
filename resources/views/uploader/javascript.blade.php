<script>

        {{-- page variables --}}

        var upload_id;
        var uploader_id;
        var dataset_id;
        var type;

        @if(isset($_GET['dataset_id'])) dataset_id = {{$_GET['dataset_id']}}; @endif

            {{-- Save information  page --}}

        $('.upload_type').click(function () {
            type = this.id;
            $('.upload_type').removeClass('selected');
            $("#" + type).addClass('selected');

        });

        $("#create_uploader").click(function(){
            $.ajax({
                url: "{{route('uploader.store')}}",
                method: 'POST',
                data: {
                    _token: "{{csrf_token()}}",
                    name: $('#upload_name').val(),
                    description: $('#upload_description').val(),
                    dataset_id: dataset_id,
                    type: type
                }
            }).success(function (data) {
                uploader_id = data.id;
                getSlide(type);
            });
        });


        var getSlide = function (slide) {
            $.ajax({
                url: '{{action('UploaderController@post')}}',
                type: 'POST',
                data: {
                    _token: "{{csrf_token()}}",
                    slug: 'slide',
                    slide: slide,
                    upload_id: upload_id,
                    uploader_id: uploader_id,
                    dataset_id: dataset_id,
                }
            }).success(function (data) {
                swapslides(data);
            });
        };

        var swapslides = function (content) {
            var card = $('#slide-card');
            var slide = $('#slide-content');
            card.addClass('bounceOutLeft');
            setTimeout(function () {
                slide.html(content);
                card.removeClass('bounceOutLeft');
                card.addClass('bounceInRight');
                setTimeout(function () {
                    card.removeClass('bounceInRight');
                }, 500);
            }, 500);

        };

</script>