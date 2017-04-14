@foreach($filters as $key => $filter)
    @if($filter['type'] == 'searchReplace')
        <div class="list-group-item filter-preview" id="{{$key}}-filter-preview">
            @include('uploader.slides.filters.searchReplace_show')
            <i class="fa fa-trash pull-right icon-button" onclick="$('#{{$key}}-filter-preview').remove()"></i>
        </div>
    @endif
@endforeach