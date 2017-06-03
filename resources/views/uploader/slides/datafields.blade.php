<h3>Data Field Map</h3>
<form action="{{route('uploader.schema')}}" method="post">
    {!! csrf_field() !!}
    <input type="hidden" name="uploader_id" value="{{$uploader->id}}">
    @if($dataset->schema != null)
        @php($schema = $dataset->schema)
        <table class="table" id="table" >
            <thead>
            <td>Ignore</td>
            <td>Visible</td>
            <td>Field Name</td>
            <td>Key</td>
            <td></td>
            <td>Maps To</td>
            <td>First Value</td>
            <td>Field Type</td>
            </thead>
            <tbody>
            @foreach($table as $key => $i)
                @include('uploader.slides.map')
            @endforeach
            </tbody>
        </table>
        <input type="submit" class="btn btn-primary" value="Update Data Set">

    @else
    <table class="table" id="table" >
        <thead>
        <td>Ignore</td>
        <td>Visible</td>
        <td>Field Name</td>
        <td>Key</td>
        <td>First Value</td>
        <td>Field Type</td>
        </thead>
        <tbody>
        @foreach($table as $i)
            @include('uploader.slides.datafield')
        @endforeach
        </tbody>
    </table>
    <input type="submit" class="btn btn-primary btn-raised" value="Create Data Set">
    @endif
</form>


<script>
    $.ajax({
        url: "{{route('uploader.schema')}}",
        method: "post",
        data: {
            _token: "{{csrf_token()}}",
            uploader_id: {{$uploader->id}},
            upload_id: {{$upload->id}},
            dataset_id: {{$dataset->id}}
        },
        success: function (data) {
            swapslides(data);
        }
    });
</script>