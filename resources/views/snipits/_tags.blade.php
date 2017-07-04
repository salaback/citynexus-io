<div class="panel-body">
    <span id="tags">
        @forelse($tags as $tag)
            @include('snipits._tag')
        @empty
        @endforelse

    </span>

    <span class="fa fa-spinner fa-spin hidden" id="pending"></span>

    @if($trashedTags->count() > 0)

        <div class="btn btn-xs btn-default btn-raised" id="show-trash" onclick="$('#trash_tags').removeClass('hidden'); $('#show-trash').addClass('hidden'); ">View Deleted Tags</div>

        <span class="hidden" id="trash_tags">
            @foreach($trashedTags as $tag)
                @include('snipits._trash_tag')
            @endforeach
        </span>

    @endif

</div>