@extends("master.main")

@section('title', 'Create New Score')

@section('main')
    <form action="{{route('score.store')}}" method="post">
    {{csrf_field()}}
    <div class="col-xs-12">
        <h1 class="font-thin h3 m-0">Create New Property Score</h1>
        <div class="row">
            <br>
        </div>
    </div>
    <div class="col-md-6">
        <section class="boxs">
            <div class="boxs-header">
                <div class="custom-font">
                    <strong>Score</strong> Information
                </div>
            </div>
            <div class="boxs-body">
                    <div class="form-group col-md-12">
                        <label for="name">Score Name </label>
                        <input type="text" name="name" id="name" class="form-control" value="{{old('name')}}"required>
                    </div>
                    {{-- Score Type --}}
                    <input type="hidden" name="type" id="type">
                    {{-- Start Stap Shot Score --}}
                    <div class="option-tile col-sm-6 score_type @if(old('type') == 'snapshot') selected @endif" onclick="setType('snapshot')" id="snapshot">
                        <div class="option-wrapper">
                            <div class="option-header">
                                Snap Shot Score
                            </div>
                            <div class="option-icon">
                                <i class="fa fa-camera-retro fa-3x"></i><br>
                                A snap shot score creates a score for a single range of time.
                            </div>
                        </div>
                    </div>
                <div class="row">
                </div>
            </div>
        </section>
        <section class="boxs">
            <div class="boxs-header">
                <div class="custom-font"><strong>Add Score</strong> Elements</div>
            </div>
            <div class="boxs-body">

                {{-- Start Updating Record --}}
                <div class="option-tile col-sm-4" data-toggle="modal" data-target="#tagModal" id="tag">
                    <div class="option-wrapper">
                        <div class="option-header">
                            Tag
                        </div>
                        <div class="option-icon">
                            <i class="fa fa-tag fa-2x"></i><br>
                            Score element based on tag
                        </div>
                    </div>
                </div>
                <div class="option-tile col-sm-4" onclick="valueType('datapoint')" id="tag">
                    <div class="option-wrapper">
                        <div class="option-header">
                            Data Point
                        </div>
                        <div class="option-icon">
                            <i class="fa fa-area-chart fa-2x"></i><br>
                            Score element based on imported data.
                        </div>
                    </div>
                </div>
                <div class="option-tile col-sm-4" onclick="valueType('comment')" id="tag">
                    <div class="option-wrapper">
                        <div class="option-header">
                            Comment
                        </div>
                        <div class="option-icon">
                            <i class="fa fa-comment fa-2x"></i><br>
                            Score element based comments
                        </div>
                    </div>
                </div>
                <div class="row">
                </div>
            </div>
        </section>
    </div>
    <div class="col-sm-6 @if(old('elements') == null) hidden @endif" id="score_elements_wrapper">
        <div class="col-sm-12 align-right">
            <button class="btn btn-primary btn-raised">Save Score</button>
        </div>
        <div class="row"></div>
        <section class="boxs">
            <div class="boxs-header">
                <div class="custom-font"><strong>Score</strong> Elements</div>
            </div>
            <div class="boxs-body">
                <div class="list-group" id="score_elements">
                    @unless(old('elements') == null)
                        @foreach(old('elements') as $element)
                            @php($element = json_decode($element, true))
                            @include('analytics.score.snipits._element')
                        @endforeach
                    @endunless
                </div>
            </div>
        </section>
    </div>
    </form>


@push('modal')
    @include('analytics.score.snipits._tags')
@endpush


@endsection

@push('scripts')
<script src="/assets/js/vendor/parsley/parsley.min.js"></script>
<script src="/assets/js/vendor/form-wizard/jquery.bootstrap.wizard.min.js"></script>

<script type="text/javascript">
    var setType = function(type) {
        $(".score_type").removeClass('selected');
        $("#" + type).addClass('selected');
        $("#type").val(type);
    };
</script>
<script>
    $('#tags').change(function (event) {
        var tag = $('tags').val();
        $.ajax({
            url: '{{route('tag.index')}}/' + tag,
            type: 'GET',
            success: function (response) {
                $('#counts').removeClass('hidden');
                console.log(response);
            }
        })
    })
</script>

@endpush