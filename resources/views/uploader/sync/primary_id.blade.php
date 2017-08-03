@extends('master.main')

@section('title', 'Primary ID Sync')

@section('main')


    <div class="col-lg-offset-1 col-lg-10 ">
        <section class="boxs">
            <div class="boxs-header dvd dvd-btm">
                <h1 class="custom-font"><strong>Create</strong> Primary ID Sync</h1>

            </div>
            <div class="boxs-body">
                <div class="tab-pane fade active in" role="tabpanel" id="unparsed" aria-labelledby="home-tab">
                    <form action="{{route('uploader.storeSync')}}" class="form-horizontal" method="post">
                        {!! csrf_field() !!}
                        <input type="hidden" name="sync[class]" value="tag">
                        <input type="hidden" name="uploader_id" value="{{$uploader->id}}">
                        <div class="row">
                            <div class="form-group" id="uniqueId">
                                <label for="unique_id" class="col-sm-3 control-label">Primary ID Column</label>
                                <div class="col-sm-4">
                                    <select name="sync[unique_id][]" class="form-control col-sm-9">
                                        <option value="">Select Field</option>
                                        @foreach($fields as $field)
                                            <option value="{{$field['key']}}">{{$field['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" id="uniqueId">
                                <label for="unique_id" class="col-sm-3 control-label">Secondary ID Column</label>
                                <div class="col-sm-4">
                                    <select name="sync[unique_id][]" class="form-control col-sm-9">
                                        <option value="">Select Field</option>
                                        @foreach($fields as $field)
                                            <option value="{{$field['key']}}">{{$field['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-raised">Save Primary ID Columns</button>
                    </form>
                </div>
            </div>
        </section>
    </div>

@endsection

@push('scripts')
<script>
    var addKey = function () {

    }
</script>
@endpush