@extends('master.main')

@section('title', 'Tag Sync')

@section('main')


    <div class="col-lg-offset-1 col-lg-10 ">
        <section class="boxs">
            <div class="boxs-header dvd dvd-btm">
                <h1 class="custom-font"><strong>Create</strong> Tag Sync</h1>

            </div>
            <div class="boxs-body">
                <div class="tab-pane fade active in" role="tabpanel" id="unparsed" aria-labelledby="home-tab">
                    <form action="{{route('uploader.storeSync')}}" class="form-horizontal" method="post">
                        {!! csrf_field() !!}
                        <input type="hidden" name="sync[class]" value="tag">
                        <input type="hidden" name="uploader_id" value="{{$uploader->id}}">
                        <div class="row">
                            <div class="form-group">
                                <label for="dataPoint" class="col-sm-3 control-label">Tag Column</label>
                                <div class="col-sm-4">
                                    <select name="sync[dataPoint]" class="form-control col-sm-9">
                                        <option value="">Select Field</option>
                                        @foreach($fields as $field)
                                            <option value="{{$field['key']}}">{{$field['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="full_address" class="col-sm-3 control-label">How to Process</label>
                                <div class="col-sm-4">
                                    <select name="sync[method]" class="form-control col-sm-9">
                                        <option value="">Select Method</option>
                                        <option value="value">Value is a tag</option>
                                        <option value="comma">Tags separated by commas</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-raised">Save Tag Sync</button>
                    </form>
                </div>
            </div>
        </section>
    </div>

@endsection