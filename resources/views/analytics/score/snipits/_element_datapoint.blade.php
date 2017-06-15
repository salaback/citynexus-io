@php($dataset = \App\DataStore\Model\DataSet::find($element['dataset_id']))

<div class="list-group-item">
    <i class="fa fa-trash pull-right" onclick="$(this.parentElement).remove()" style="cursor: pointer"></i>
    <input type="hidden" name="elements[]" value='{{ json_encode($element) }}'>
    <div class="row">
        <div class="col-sm-1">
            <i class="fa fa-database fa-2x"></i>
        </div>
        <div class="col-sm-6">
            <h4>{{$dataset->name}} > {{$element['key']}}</h4>
            @if($element['properties']['units'] == 'total')
                <span class="label label-default">
                Building Totals
            </span>&nbsp
            @elseif($element['properties']['units'] == 'average')
                    <span class="label label-default">
                Per Unit Average
            </span>&nbsp
            @endif
            @if($element['properties']['property'] != 'false')
                <span class="label label-default">
                Properties with data
            </span>&nbsp
            @endif
        </div>
        <div class="col-sm-4">
            @if($element['effect']['type'] == 'range')
                If data point is
                @if($element['effect']['range']['equalTo' != 'false'])
                   is equal to {{$element['effect']['range']['equalTo']}}
                @else
                    @if($element['effect']['range']['greaterThan'] != 'false')
                        greater than {{$element['effect']['range']['greaterThan']}}
                    @endif
                    @if($element['effect']['range']['greaterThan'] != 'false')
                        @if($element['effect']['range']['greaterThan'] != 'false')
                            while
                        @endif
                        less than {{$element['effect']['range']['lessThan']}}
                    @endif
                @endif
                add to score {{$element['effect']['range']['add']}}
            @else
                {{title_case($element['effect']['type'])}}
                @if($element['effect']['type'] == 'value')
                    data point value
                @elseif($element['effect']['type'] == 'zscore')
                    z-score of data point value
                @elseif($element['effect']['type'] == 'log')
                    log of data point value
                @elseif($element['effect']['type'] == 'index')
                    index score of data point value
                @elseif($element['effect']['type'] == 'square')
                    square of data point value
                @elseif($element['effect']['type'] == 'cube')
                    cube of data point value
                @endif
            @endif
        </div>
    </div>
</div>