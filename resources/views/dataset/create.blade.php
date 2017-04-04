@extends("master.main")

@section('title', "Create New Data Set")

@section('main')
    <form action="{{route('dataset.store')}}" method="post">
        {{csrf_field()}}
        <input type="hidden" name="type" id="type" value="{{old('type')}}">
        <div class="col-lg-offset-1 col-lg-10 animated" id="slide-card">
            <div class="card-box p-b-0">
                <div class="slide-contents" id="slide-content">
                    @include('layouts._form_error')
                    <div class="row">
                        <div class="row">
                            <div class="form-horizontal">
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
                            </div>


                            <h3>What Type of Data Set?</h3>
                            {{-- Start Primary Record Tile --}}
                            <div class="option-tile col-sm-4 data_type @if(old('type') == 'profile') selected @endif" onclick="setType('profile')" id="profile">
                                <div class="option-wrapper">
                                    <div class="option-header">
                                        Profile Record
                                    </div>
                                    <div class="option-icon">
                                        <i class="fa fa-file-text-o fa-3x"></i><br>
                                        Records like department record where there is only one record per property, but the time
                                        series information may be important.
                                    </div>
                                </div>
                            </div>

                            {{-- Start Fixed Record Tile --}}
                            <div class="option-tile col-sm-4 data_type @if(old('type') == 'fixed') selected @endif"  onclick="setType('fixed')" id="fixed">
                                <div class="option-wrapper">
                                    <div class="option-header">
                                        Fixed Record
                                    </div>
                                    <div class="option-icon">
                                        <i class="fa fa-sticky-note-o fa-3x"></i><br>
                                        Records like an emergency services call for service which once created won't be changed.
                                    </div>
                                </div>
                            </div>

                            {{-- Start Updating Record --}}
                            <div class="option-tile col-sm-4 data_type @if(old('type') == 'updating') selected @endif" onclick="setType('updating')" id="updating">
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
                    <br>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="submit" class="btn btn-primary" value="Create Data Set">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection

@push('javascript')
<script>
    var setType = function(type)
    {
        $(".selected").removeClass('selected');
        $("#" + type).addClass('selected');
        $("#type").val(type);
    }
</script>

@endpush