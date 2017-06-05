@extends('master.main')

@section('title', 'All Properties')

@section('main')

    <div class="row">
        <div class="col-md-12">
            <section class="boxs ">
                <div class="boxs-header dvd dvd-btm">
                    <h1 class="custom-font"><strong>All </strong>Properties</h1>

                </div>
                <div class="boxs-body">
                    <table id="propertiesTable" class="table table-custom">
                        <thead>
                        <tr>
                            <th>Address</th>
                            <th>Units</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
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
        $('#propertiesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('property.allData') !!}',
            columns: [
                { data: 'address', name: 'address' },
                { data: 'units', name: 'units', orderable: false, searchable: false },
                { data: 'actions', name: 'actions', orderable: false, searchable: false}
            ]
        });
    });
</script>

@endpush