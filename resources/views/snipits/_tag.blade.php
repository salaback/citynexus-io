<div class="label label-default" id="tag-{{$tag->id}}"
     @if(isset($tag->pivot->created_at) && $tag->pivot->created_by)
     data-toggle="tooltip" data-placement="bottom" title="Tagged: {{date_format($tag->pivot->created_at,"m/d/Y")}} by {{\App\User::find($tag->pivot->created_by)->fullname}}"
        @endif
    >
    <i class="glyphicon glyphicon-tag"></i>
    {{$tag->tag}}
    <span class="fa fa-times" style="cursor: pointer" onclick="removeTag({{$tag->pivot->id}}, {{$tag->id}})" id="delete-tag-{{$tag->id}}"></span>
</div>
&nbsp

