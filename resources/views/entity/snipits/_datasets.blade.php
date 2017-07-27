
<section class="boxs">
    <div class="boxs-body">
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            @php($properties = \App\PropertyMgr\Model\Property::all())

            @foreach($entity->datasets as $key => $dataset)
                {{dd('asdfasdf')}}

                @php($table = \App\DataStore\Model\DataSet::find($key))

                @php($schema = $table->schema)

                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="{{$key}}_tab">
                        <h4 class="panel-title"> <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#{{$key}}_body" aria-expanded="false" aria-controls="collapseTwo">{{$table->name}} ({{count($dataset)}})</a> </h4>
                    </div>
                    <div id="{{$key}}_body" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                        <div class="panel-body dataset-body">
                            <div class="boxs-body p-0">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>Property</th>
                                        @foreach($schema as $item)
                                            @if(isset($item['show']) && $item['show'] == 'on')<th>{{$item['name']}}</th>@endif
                                        @endforeach
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($dataset as $k => $i)
                                        <tr>
                                            <td><a href="{{route('properties.show', [$i->__property_id])}}">{{$properties->find($i->__property_id)->oneLineAddress}}</a></td>
                                            @foreach($i as $column => $line)
                                                @if(isset($schema[$column]) && isset($schema[$column]['show']) && $schema[$column]['show'] == 'on')
                                                    <td>{{$line}}</td>
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
