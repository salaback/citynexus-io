@extends('uploader.slides.filters.filter')

@section('main')

    <div class="list-group">
        <a href="#" class="list-group-item" onclick="addFilter('searchReplace')">
            Search and Replace
        </a>
    </div>

    <div id="filters-queue">

    </div>

@endsection