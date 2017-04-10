<div class="comment" id="comment-{{$comment->id}}">
    <div class="comment-footer pull-right">
        <i class="fa fa-reply" style="cursor: pointer" onclick="replyToComment({{$comment->id}}, '{{$comment->poster->fullname}}')"></i>
        @if($comment->poster->id == \Illuminate\Support\Facades\Auth::getUser()->id | \Illuminate\Support\Facades\Auth::getUser()->super_admin == true)<div style="color: red; cursor: pointer" class="glyphicon glyphicon-trash " onclick="deleteComment({{$comment->id}})"></div>@endif
    </div>
    <div class="comment-byline" data-toggle="tooltip" data-placement="top" title="{{date_format($comment->created_at,"m/d/Y")}}"
    >{{$comment->poster->fullname}} - {{$comment->created_at->diffForHumans()}}</div>
    <div class="comment-body">{{$comment->comment}}</div>

    <div class="replies @unless($comment->comments->count() > 0) hidden @endunless" id="reply-comments-{{$comment->id}}">
        @if($comment->comments->count() > 0)
            @each('property.snipits._comment', $comment->replies, 'comment')
        @endif
    </div>
</div>