@extends('master.main')

@section('title', "Create Calculated Value")

@section('main')
    <div class="col-md-12">
        <section class="boxs">
            <form action="{{route('calculated-value.store')}}" method="POST" role="form">
                <div class="boxs-header dvd dvd-btm">
                    <h1 class="custom-font"><strong>Create</strong> Calculated Value</h1>
                </div>
                <div class="boxs-body">
                    {{csrf_field()}}
                    <legend>Create Z Score</legend>
                    <input type="hidden" name="type" value="zscore">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="table">Calculate Value Name</label>
                            <input type="text" class="form-control" name="name" id="name">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="table">Select Table</label>
                            <select class="form-control" name="settings[table]" id="tables">
                                <option value="">Select One</option>
                                @foreach($tables as $table)
                                    <option value="{{$table->id}}">{{$table->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="table">Select Field</label>
                            <select class="form-control" name="settings[field]" id="fields">
                            </select>
                        </div>
                    </div>
                    <input type="submit" value="Create New Value" class="btn btn-raised btn-info">
                </div>
            </form>
        </section>
    </div>
@endsection

@push('scripts')

<script>

    @php
        $datasets = [];
        foreach($tables as $table) $datasets[$table->id] = $table->schema;
    @endphp

    var datasets = {!! json_encode($datasets) !!}

    $('#tables')
        .change(function () {
            var table_id = $('#tables').val();
            var data = datasets[table_id];
            var fields = $('#fields');
            fields.html(null);
            setOption(fields, null, 'Select One');
            $.each(data, function(key, value){
                if(value.type == 'integer' || value.type == 'float')
                    {
                        setOption(fields, value.key, value.name)

                    }
                }
            );
        });

    var setOption = function(target, key, name)
    {
        target.append("<option value='" + key + "'>" + name + "</option>")
    }

</script>
@endpush