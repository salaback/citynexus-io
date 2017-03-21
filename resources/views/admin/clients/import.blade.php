@extends(config('citynexus.template'))

@section(config('citynexus.section'))

        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="panel-title">Create Client</span>
            </div>
            <div class="panel-body">
                <table class="table table-hover">
                	<thead>
                		<tr>
                			<th>Table Name</th>
                            <th>Count</th>
                            <th>Corresponding Table Exists</th>
                            <th></th>
                		</tr>
                	</thead>
                	<tbody>
                    @foreach($results as $table => $count)
                        @if(str_contains($table, ['tabler_']) && $table != 'tabler_tables')
                            @if(isset($datasets[$table]))
                                <tr>
                                    <td>{{$table}}</td>
                                    <td>{{$count}}</td>
                                    <td id="{{$table}}_count">@if(isset($current[$table])) {{$current[$table]}} @endif</td>
                                    <td>@if(!isset($current[$table]) || $count != $current[$table])
                                            <button class="btn btn-primary" id="{{$table}}_button"
                                                    @if(str_contains($table, ['citynexus_scores_']))
                                                    onclick="importTable('{{$table}}', 'score')"
                                                    @elseif(str_contains($table, ['tabler_']) && $table != 'tabler_tables')
                                                    onclick="importTable('{{$table}}', 'data_table')"
                                                    @else
                                                    onclick="importTable('{{$table}}', 'migrate')"
                                                    @endif
                                            >Migrate</button>
                                        @else
                                            <div class="btn btn-success">Synced</div>
                                        @endif
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td>{{$table}}</td>
                                    <td>{{$count}}</td>
                                    <td id="{{$table}}_count">@if(isset($current[$table])) {{$current[$table]}} @endif</td>
                                    <td>
                                        Table Deleted
                                    </td>
                                </tr>
                            @endif
                        @else
                            <tr>
                                <td>{{$table}}</td>
                                <td>{{$count}}</td>
                                <td>@if(isset($current[$table])) {{$current[$table]}} @endif</td>
                                <td>@if(!isset($current[$table]) || $count != $current[$table])
                                        <button class="btn btn-primary" id="{{$table}}_button"
                                                @if(str_contains($table, ['citynexus_scores_']))
                                                onclick="importTable('{{$table}}', 'score')"
                                                @elseif(str_contains($table, ['tabler_']) && $table != 'tabler_tables')
                                                onclick="importTable('{{$table}}', 'data_table')"
                                                @elseif($table == 'users')
                                                onclick="importTable('{{$table}}', 'users')"
                                                @else
                                                onclick="importTable('{{$table}}', 'migrate')"
                                                @endif
                                        >Migrate</button>
                                    @else
                                        <div class="btn btn-success">Synced</div>
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                	</tbody>
                </table>

                </div>
            </div>
            <div class="panel-footer">
                <button type="submit" class="btn btn-primary">Create Client</button>
            </div>
@endsection

@push('scripts')

<script>
    var importTable = function(table, type)
    {
        $('#' + table + '_button').html("<i class='fa fa-spinner fa-spin'></i>");

        $.ajax({
            url: '/admin/client/import-table/',
            type: 'POST',
            data: {
                _token: "{{csrf_token()}}",
                client_id: {{$client->id}},
                table: table,
                host: "{{$importDb['host']}}",
                database: "{{$importDb['database']}}",
                username: "{{$importDb['username']}}",
                password: "{!! $importDb['password'] !!}",
                schema: "{{$importDb['schema']}}",
                type: type
            }
        }).success(function(data){
            $('#' + table + '_button').html("Migrated");
            $('#' + table + '_count').html(data);

            $('#' + table + '_button').removeClass("btn-primary").addClass('btn-success');

        }).error(function(){
            $('#' + table + '_button').html("Error");
            $('#' + table + '_button').removeClass("btn-primary").addClass('btn-warning');
        });
    }
</script>

@endpush