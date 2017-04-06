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
    </div>

@endsection

@push('style')

@endpush
@push('javascript')

@endpush