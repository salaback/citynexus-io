<div class="list-group-item">
    <i class="fa fa-trash pull-right" onclick="$(this.parentElement).remove()" style="cursor: pointer"></i>
    <input type="hidden" name="elements[]" value='{!! json_encode($element) !!}'>
    <div class="row">
        <div class="col-sm-1">
            <i class="fa fa-comment fa-2x"></i>
        </div>
        <div class="col-sm-6">
            <h4>Comment Search</h4>
            For comments which
            @if($element['method'] != 'include')
                include
            @else
                don't include
            @endif
            "{{$element['query']}}".
        </div>
        <div class="col-sm-4">
            @if($element['effect']['type'] == 'ignore')
                Properties with matching comments will be ignored from score.
            @elseif($element['effect']['type'] == 'add')
                {{$element['effect']['factor']}} {{str_plural('point', $element['effect']['factor'])}} added to score
            @elseif($element['effect']['type'] == 'subtract')
                {{$element['effect']['factor']}} {{str_plural('point', $element['effect']['factor'])}} subtracted from score
            @endif
            @if($element['effect']['scope'] == 'unit')
                for each matching unit.
            @elseif($element['effect']['scope'] == 'building')
                for each building with a matching comment.
            @elseif($element['effect']['scope'] == 'all')
                for each matching comment.
            @endif
        </div>
    </div>
</div>