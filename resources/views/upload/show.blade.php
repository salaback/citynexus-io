@extends("master.main")

@section('title', "Upload Information")

@section('main')

    <div class="row">
        <div class="col-md-4">
            <section class="boxs ">
                <div class="boxs-header dvd dvd-btm">
                    <h1 class="custom-font"><strong>Upload </strong>Information</h1>

                </div>
                <div class="boxs-body">
                    <strong>Uploaded By: </strong> {{$upload->user->fullname}} <br>
                    <strong>Uploaded At: </strong> {{$upload->updated_at->toFormattedDateString()}} <br>
                    <strong>Records Added: </strong> {{$upload->size}} <br>
                    <strong>New Properties: </strong> {{count($upload->new_property_ids)}} <br>
                </div>
            </section>
        </div>
        <div class="col-md-8">
            <section class="boxs ">
                <div class="boxs-header dvd dvd-btm">
                    <h1 class="custom-font"><strong>Uploaded </strong>Data</h1>

                </div>
                <div class="boxs-body p-0 data-preview">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            @foreach($data->first() as $key => $item)
                            <th>{{$key}}</th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $line)
                                <tr>
                                    @foreach($line as $value)
                                    <td>{{$value}}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
            <section class="boxs ">
                <div class="boxs-header dvd dvd-btm">
                    <h1 class="custom-font"><strong>New Properties </strong>Created</h1>
                </div>
                <div class="boxs-body p-0 data-preview">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Address</th>
                            <th>Is Building</th>
                            <th>Is Unit</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($properties as $property)
                            <tr>
                                <td>{{$property->OneLineAddress}}</td>
                                <td>
                                    @if($property->is_building) <i class="fa fa-check"></i>@endif
                                </td>
                                <td>
                                    @if($property->is_unit) <i class="fa fa-check"></i>@endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>

@endsection

@push('style')

@endpush
@push('javascript')

@endpush