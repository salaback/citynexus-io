@php($tag = \App\PropertyMgr\Model\Tag::find($element['tag_id']))

<div class="list-group-item">
    <i class="fa fa-trash pull-right" onclick="$(this.parentElement).remove()" style="cursor: pointer"></i>
    <input type="hidden" name="elements[]" value='{!! json_encode($element) !!}'>
    <div class="row">
        <div class="col-sm-1">
            <i class="fa fa-tag fa-2x"></i>
        </div>
        <div class="col-sm-6">
            <h4>{{$tag->tag}}</h4>
            @if($element['tags']['tagged'] != 'false')
                <span class="label label-default">
                Tagged properties
            </span>&nbsp
            @endif
            @if($element['tags']['tagged_range'] != 'false')
                <span class="label label-default">
                Within {{$element['tags']['tagged_range']}}m of tagged properties
            </span>&nbsp
            @endif
            @if($element['tags']['trashed'] != 'false')
                <span class="label label-default">
                Previously tagged properties
            </span>&nbsp
            @endif
            @if($element['tags']['trashed_range'] != 'false')
                <span class="label label-default">
                Within {{$element['tags']['tagged_range']}}m of previously tagged properties
            </span>
            @endif
        </div>
        <div class="col-sm-4">
            @if($element['effect']['type'] == 'ignore')
                Affected properties will be ignored from score.
            @elseif($element['effect']['type'] == 'add')
                {{$element['effect']['factor']}} {{str_plural('point', $element['effect']['factor'])}} added to score.
            @elseif($element['effect']['type'] == 'subtract')
                {{$element['effect']['factor']}} {{str_plural('point', $element['effect']['factor'])}} subtracted from score.
            @endif
        </div>
    </div>
</div>