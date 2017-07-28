@extends('master.main')

@section('title', $score->name)

@section('main')
    <a href="{{route('score.refresh', [$score->id])}}" class="btn btn-primary btn-raised">Refresh Score</a>
    <h1 class="custom-font">Score Results</h1>
        <div class="boxs-body p-0">
            {{$results->links()}}
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Property</th>
                    <th>Score</th>
                    <th>Elements</th>
                </tr>
                </thead>
                <tbody>
                @foreach($results as $result)
                    <tr>
                        <td class="col-sm-1"></td>
                        <td class="col-sm-3"><a href="{{route('properties.show', [$result->property_id])}}">{{title_case($result->address)}}</a></td>
                        <td class="col-sm-1" ><span data-toggle="tooltip" title="{{$result->score}}">{{round($result->score, 2)}}</span><td>
                        <div class="progress">
                            @foreach(json_decode($result->elements) as $key => $value)
                                @foreach($value as $item)
                                    @if($item->type == 'datapoint')
                                        <div class="progress-bar progress-bar-danger" data-toggle="tooltip" title="{{$datasets->where('id', $item->dataset_id)->first()->name}} > {{$item->key}} {{round($item->effect, 2)}}" data-placement="top" style="width: {{($item->effect / $scores['max'] * 100)}}%; opacity: {{$shades['datapoints'][$item->dataset_id . '_' . $item->key]}}"> <span class="sr-only">35% Complete</span> </div>
                                    @endif
                                @endforeach
                            @endforeach
                        </div>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{$results->links()}}
        </div>
    </div>

@endsection