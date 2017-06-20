@if($element['type'] == 'tag')
    @include('analytics.score.snipits._element_tag')
@elseif($element['type'] == 'datapoint')
    @include('analytics.score.snipits._element_datapoint')
@elseif($element['type'] == 'comment')
    @include('analytics.score.snipits._element_comment')
@endif