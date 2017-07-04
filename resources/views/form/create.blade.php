@extends('master.main')

@section('title', 'Create New Form')

@section('main')

    <div class="row">
        <select name="datasets" id="datasets">
            <option value="">Select One</option>
            @foreach($datasets as $i)
                <option value="{{$i->id}}">{{$i->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="boxs">
                <div class="boxs-header">
                    <h1 class="custom-font">Form Elements</h1>
                </div>
                <div class="boxs-body">
                    <div class="element-btn">
                        Text Field
                    </div>
                    <div class="element-btn">
                        Select List
                    </div>
                    <div class="element-btn">
                        Link to Property
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-8">

            <div class="boxs">
                <div class="boxs-header">
                    <h1 class="custom-form">Form Preview</h1>
                </div>
                <div class="boxs-body">
                    <div class="element text">
                        <div class="type">Text area</div>
                        <div class="row">
                            <div class="col-sm-6">
                                <input type="text" name="fields[][name]" class="form-control" placeholder="Field Name">
                            </div>
                            <div class="col-sm-6">
                                <select name="fields[][map]">
                                    <option value="">Select One</option>
                                    @foreach($text as $i)
                                        <option value="{{$i->key}}">{{$i->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

@push('style')
<style>
    .element-btn {
        padding: 20px;
        margin: 5px;
        background-color: #00a5bb;
    }


</style>
@endpush

@push('scripts')

<script>
    var datasets =
    {

    }
</script>
@endpush