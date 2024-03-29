@extends('master.main')

@section('title', 'All Data Sets')

@section('main')

    <div class="row">
        <div class="col-md-12">
            <section class="boxs ">
                <div class="boxs-header dvd dvd-btm">
                    <h1 class="custom-font"><strong>Data</strong> Sets</h1>
                    @can('citynexus', ['datasets', 'create'])<ul class="controls">
                        @can('citynexus', ['datasets', 'create'])<li><a href="{{route('dataset.create')}}"><i class="fa fa-plus mr-5"></i> Create New Data Set</a></li>@endcan
                    </ul>
                    @endcan
                </div>
                <div class="boxs-body">
                    <table id="datasets" class="table table-custom">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Updated At</th>
                            <th>Owner</th>
                            <th></th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </section>
        </div>
    </div>

@endsection

@push('style')

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">

@endpush

@push('scripts')

<script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>

<script>
    $(function() {
        $("#datasets").DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{route('dataset.anydata')}}',
            columns: [
                {data: 'name', name: 'name'},
                {data: 'updated', name: 'updated_at'},
                {data: 'owner', name: 'owner'},
                {data: 'settings', name:'settings'}
            ],
            dom: "Bfrtip",
            buttons: [{extend: "copy", className: "btn-sm"}, {extend: "csv", className: "btn-sm"}, {
                extend: "excel",
                className: "btn-sm"
            }, {extend: "pdf", className: "btn-sm"}, {extend: "print", className: "btn-sm"}],

        });
    });
</script>

@endpush