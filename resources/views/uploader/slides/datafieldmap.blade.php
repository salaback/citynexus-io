<h3>Data Field Map</h3>
<form action="{{route('uploader.schema')}}" method="post">
    {!! csrf_field() !!}
    <input type="hidden" name="uploader_id" value="{{$uploader->id}}">
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

    <input type="submit" class="btn btn-primary" value="Create Dataset">
</form>