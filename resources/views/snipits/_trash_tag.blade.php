<span class="label label-danger" id="tag-{{$tag->id}}"
     @if(isset($tag->pivot->created_at) && $tag->pivot->created_by)
     data-toggle="tooltip" data-placement="bottom" title="Tagged: {{date_format($tag->pivot->created_at,"m/d/Y")}} by {{\App\User::find($tag->pivot->created_by)->fullname}}
        | Deleted: {{date('m/d/Y', strtotime($tag->pivot->deleted_at))}} by {{\App\User::find($tag->pivot->deleted_by)->fullname}}"
        @endif
>
    <i class="fa fa-trash"></i>
    {{$tag->tag}}
</span>
