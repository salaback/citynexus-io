
@extends("layouts.app")

@section('title', "Add Filters")

@section('main')

    <!-- BASIC WIZARD -->
    <div class="col-lg-offset-1 col-lg-10 animated" id="slide-card">
        <div class="card-box p-b-0">
            <div class="slide-contents" id="slide-content">
                <div class="row">

                    <div class="row">

                        <h3>Add Data Filters</h3>

                        <div class="col-sm-offset-2 col-sm-8">
                            <form action="{{action('UploaderController@post')}}" method="post">
                                {!!  csrf_field() !!}
                                <input type="hidden" name="uploader_id" value="{{$uploader->id}}">
                                <input type="hidden" name="slug" value="commit_filters">
                                @foreach($datafields as $field)
                                    <div class="list-group-item">
                                        {{$field['name']}}
                                        <div class="btn btn-primary btn-xs pull-right" onclick="getFilters('{{$field['type']}}', '{{$field['key']}}')">Add Filter</div>
                                        <div id="filters-{{$field['key']}}" class="list-group filter-preview"></div>
                                    </div>
                                @endforeach

                                <button class="btn btn-primary">Save Filters</button>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- end col -->

@endsection

@push('style')

<style>

    .slide-contents {
        padding: 10px;
    }
    .option-tile {
        border: 3px solid white;
        background: #003f5e;
        min-height: 200px;
        color: white

    }
    .option-title .selected {
        border: #c93635;
    }
    .option-tile:hover {
        cursor: pointer;
        background: #006692;
    }
    .option-tile:active {
        background: #004f74;
    }
    .option-wrapper {
        padding: 15px;
        vertical-align: center;
    }
    .option-header{
        text-align: center;
        font-size: 20px;
        font-weight: 100;
        padding-bottom: 10px;
    }
    .option-icon{
        text-align: center;
    }
</style>

@endpush

@push('javascript')
@include('uploader.slides.filters_javascript')
@endpush