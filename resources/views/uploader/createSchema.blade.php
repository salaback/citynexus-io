@extends('master.main')

@section('title', 'Map Data Fields')

@php($schema = $uploader->dataset->schema)

@section('main')

<div class="boxs">
    <div class="boxs-header">
        <h1 class="custom-font">Map Data Fields</h1>
    </div>
    <div class="boxs-body">
        <form action="{{route('uploader.storeMap')}}" method="post">
            {!! csrf_field() !!}
            <input type="hidden" name="uploader_id" value="{{$uploader->id}}">
            <table class="table" id="table" >
                <thead>
                <td>Visible</td>
                <td>Field Name</td>
                <td>Map To</td>
                <td>First Value</td>
                <td>Field Type</td>
                </thead>
                <tbody>
                @foreach($uploader->map as $i)
                    @include('uploader.snipits._datafield')
                @endforeach
                </tbody>
            </table>

            <input type="submit" class="btn btn-primary btn-raised" value="Update Data Set">
        </form>
    </div>
</div>

@endsection

@push('style')
<style>
    option.select-hr { border-bottom: 1px dotted #000; }
</style>
@endpush

@push('scripts')
<script>
    var schema = {!! json_encode($schema) !!};
    $('.mapto').change(function(event){
        if(event.target.value == 'create') {
            $('#mapto-wrapper-' + event.target.dataset.key).removeClass('hidden');
            $('#mapto-' + event.target.dataset.key).val('');
            $('#default-type-' + event.target.dataset.key).addClass('hidden');
            $('#type-' + event.target.dataset.key).removeClass('hidden');
            $('#is-new-' + event.target.dataset.key).val(event.target.dataset.key);
        } else if (event.target.value == 'ignore')
        {
            $('#mapto-wrapper-' + event.target.dataset.key).addClass('hidden');
            $('#mapto-' + event.target.dataset.key).val('');
            $('#default-type-' + event.target.dataset.key).addClass('hidden');
            $('#type-' + event.target.dataset.key).addClass('hidden');
            $('#is-new-' + event.target.dataset.key).val();
        } else {
            $('#is-new-' + event.target.dataset.key).val();
            $('#mapto-wrapper-' + event.target.dataset.key).addClass('hidden');
            $('#mapto-' + event.target.dataset.key).val(event.target.value);
            $('#default-type-' + event.target.dataset.key).removeClass('hidden').html(schema[event.target.value]['type']);
            $('#type-' + event.target.dataset.key).addClass('hidden');
        }
    });

    $('.map-to-new').change(function(event){
        $('#mapto-' + event.target.dataset.key).val(event.target.value);
        $('#is-new-' + event.target.dataset.key).val(event.target.dataset.key);
    });
</script>
@endpush