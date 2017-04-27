@extends('master.main')

@section('title', 'All Calculated Values')

@section('main')

    <div class="row">
        <div class="col-md-12">
            <section class="boxs ">
                <div class="boxs-header dvd dvd-btm">
                    <h1 class="custom-font"><strong>All </strong>Calculated Values</h1>

                </div>
                <div class="boxs-body">
                    <table id="propertiesTable" class="table table-custom">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Owned By</th>
                            <th>Updated</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($values as $value)
                                <tr>
                                    <td>{{$value->name}}</td>
                                    <td>{{$value->type}}</td>
                                    <td>{{$value->creator->full_name}}</td>
                                    <td id="updated-{{$value->id}}">{{$value->updated_at->diffForHumans()}}</td>
                                    <td><button class="refresh btn btn-raised btn-primary btn-sm" id="refresh-{{$value->id}}" data-id="{{$value->id}}">Refresh</button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>

@endsection


@push('scripts')

<script>
    var test;
    $('.refresh').click(function(event) {
        var button = $('#' + event.currentTarget.id);
        button.html('<i class="fa fa-spin fa-spinner"></i>');

        $.ajax({
                    url: "{{route('calculated-value.refresh')}}",
                    type: 'POST',
                    data: {
                        _token: "{{csrf_token()}}",
                        id: event.currentTarget.dataset.id
                    },
                    success: function() {
                      button.removeClass('btn-primary').addClass('btn-success').html('Refreshed');
                        $("#updated-" + event.currentTarget.dataset.id).html('Just now')
                    },
                    error: function() {
                        button.html('Error').removeClass("btn-primary").addClass('btn-warning');

                    }
        });
    })
</script>

@endpush