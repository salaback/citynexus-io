<ul class="list-group">
    @foreach($property->files as $file)
        @php($type = $file->type)
        @if($type)
            <li class="list-group-item" @if(substr($type, 0, 6) == 'image/') onclick="showImage({{$file->id}})" @else onclick="downloadFile({{$file->id}})" @endif style="cursor: pointer">
                @if(
                $type == 'application/pdf' ||
                $type == 'application/x-pdf'
                )
                    <i class="fa fa-file-pdf-o"> </i>
                @elseif(substr($type, 0, 6) == 'image/')
                    <i class="fa fa-image-o"> </i>
                @elseif($type == 'application/msword')
                    <i class="fa fa-file-word-o"> </i>
                @elseif($type == 'application/mspowerpoint')
                    <i class="fa fa-file-powerpoint-o"> </i>
                @elseif($type == 'application/msexcel')
                    <i class="fa fa-file-excel-o"> </i>
                @else
                    <i class="fa fa-file"></i>
                @endif
                {{$file->name}} ({{$file->updated_at->diffForHumans()}})</li>
        @endif
    @endforeach
</ul>

@push('scripts')
<script>
    function showImage(id)
    {
        $.ajax({
            url: '{{route('files.index')}}/' + id,
        }).success(function(data){
            var file = '<a href="' + data.source + '" target="_blank"><img style="max-width: 90%" class="model_file" src="' + data.source + '"/></a>'+
                    @can('citynexus', ['property', 'delete'])
                            '<br><a class="pull-right" href="/citynexus/file/delete/' + id + '">' +
                    '<i class="fa fa-trash"></i> </a>' +
                    @endcan
                            '<p>' + data.description + '</p>';
            triggerModal(data.caption, file);

        });
    }

    function downloadFile(id) {
        window.open("{{route('files.index')}}/" + id);
    }
</script>
@endpush

@push('modal')

@endpush