@extends("master.main")

@section('title', "Create New Data Set")

@section('main')

    <form action="{{route('dataset.store')}}" method="post" class="form-horizontal">
        {{csrf_field()}}
        <input type="hidden" name="type" id="type" value="{{old('type')}}">
        <div class="col-sm-offset-1 col-sm-10">
            <section class="boxs">
                <div class="boxs-header dvd dvd-btm">
                    <h1 class="custom-font"><strong>Create</strong> New Data Set</h1>
                </div>
                <div class="boxs-body">
                    @include('master._form_error')
                    <div class="form-group">
                        <label for="name" class="col-sm-2">
                            Data Set Name
                        </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="name" id="name" value="{{old('name')}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description" class="col-sm-2">
                            Description
                        </label>
                        <div class="col-sm-10">
                            <textarea name="description" class="form-control" id="description" cols="30" rows="5">{{old('description')}}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description" class="col-sm-2">
                            Data Set Owner
                        </label>
                        <div class="col-sm-10">
                            <select name="owner_id" id="owner" class="form-control" required>
                                <option value="">Select One</option>
                                @foreach($users as $user)
                                    <option value="{{$user->id}}" @if(\Illuminate\Support\Facades\Auth::Id() == $user->id) selected @endif>{{$user->fullname}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <h4> What Type of Data Set?</h4>
                    {{-- Start Primary Record Tile --}}
                    <div class="row datatypes">
                        {{--<div class="option-tile col-sm-6 data_type @if(old('type') == 'profile') selected @endif" onclick="setType('profile')" id="profile">--}}
                            {{--<div class="option-wrapper">--}}
                                {{--<div class="option-header">--}}
                                    {{--Profile Record--}}
                                {{--</div>--}}
                                {{--<div class="option-icon">--}}
                                    {{--<i class="fa fa-file-text-o fa-3x"></i><br>--}}
                                    {{--Records like department record where there is only one record per property, but the time--}}
                                    {{--series information may be important.--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{-- Start Updating Record --}}
                        <div class="option-tile col-sm-6 data_type @if(old('type') == 'updating') selected @endif" onclick="setType('updating')" id="updating">
                            <div class="option-wrapper">
                                <div class="option-header">
                                    Updating Record
                                </div>
                                <div class="option-icon">
                                    <i class="fa fa-copy fa-3x"></i><br>
                                    A record like a building permit or citation, which might have updates to some fields.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="boxs-footer">
                    <input type="submit" class="btn btn-primary btn-raised" value="Create Data Set">
                </div>
            </section>
        </div>
    </form>

@endsection

@push('style')

<style>
    .data_type {
        cursor: pointer;
    }
    .datatypes {
        padding-left: 12px;
        padding-right: 12px;
    }


</style>
@endpush

@push('scripts')
<script>
    var setType = function(type)
    {
        $(".selected").removeClass('selected');
        $("#" + type).addClass('selected');
        $("#type").val(type);
    }
</script>

@endpush