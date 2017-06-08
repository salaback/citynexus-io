<div class="comments" id="comments">
    @forelse($comments as $comment)
        @include('snipits._comment')
    @empty
        <div class="list-group-item alert alert-info" id="no-comments">
            No comments yet.
        </div>
    @endforelse
</div>

<div class="form">
    <div id="replyTo" class="hidden">
        <input type="hidden" id="reply_comment_id">
        <span id="reply_to_name"></span>
        <span class="label label-default pull-right" onclick="removeReplyToComment()" style="cursor: pointer"><i id="fa fa-times-circle-o"></i> Remove Reply</span>
    </div>
    <textarea name="comment" id="comment" cols="30" rows="5" class="form-control" placeholder="Add a comment..."></textarea>
    <br/>
    <button class="btn btn-raised btn-default" onclick="saveComment()">Add Comment</button>
</div>


@push('scripts')
<script>

    function replyToComment(comment_id, name) {
        $('#reply_to_name').html('@ ' + name);
        $('#reply_comment_id').val(comment_id);
        $('#replyTo').removeClass('hidden');
        window.location.href = "#replyTo";
    };

    function removeReplyToComment(){
        $('#replyTo').addClass('hidden');
        $('#reply_comment_id').val(null);
    };

    function saveComment()
    {
        var comment = $('#comment').val();

        $.ajax({
            url: "{{route('comments.store')}}",
            type: "Post",
            data: {
                _token: "{{csrf_token()}}",
                comment: comment,
                posted_by: {{\Illuminate\Support\Facades\Auth::id()}},
                reply_to: $('#reply_comment_id').val(),
                cn_commentable_type: '{!! $model !!}',
                cn_commentable_id: {{$model_id}},
            }
        }).success(function( data )
        {
            if($('#reply_comment_id').val() != '')
            {
                $('#reply-comments-' + $('#reply_comment_id').val()).prepend( data ).removeClass('hidden');
                removeReplyToComment();
            }
            else {
                $('#comments').prepend( data );
                $('#no-comments').addClass('hidden');
                window.location.href = "#comments";
            }
            $('#comment').val( null );
        })
    };

    function deleteComment( id  )
    {
        $.ajax({
            url: "/" + id,
            type: 'GET'
        }).success( function() {
            $("#comment-" + id).addClass('hidden');
        })
    };
</script>

@endpush

@push('style')
<style>
    #replyTo {
        height: 30px;
        width: 100%;
        padding: 5px;
        font-size: .9em;
        background-color: lavender;
    }

    .comments {
        padding-left: 10px;
    }
    .comment-body {
        border-bottom: 1px dashed;
        padding-bottom: 5px;
    }

    .comments .comment {
        padding: 5px;
        width: 100%;
        background-color: transparent;
    }

    .comment-footer {
        padding-bottom: 5px;
        padding-top: 10px;
        font-size: .9em;
    }
    .replies .comment {
        border-bottom: none;
        border-left: groove;

    }
    .comment-byline {
        color: darkgrey;
        font-size: .8em;
    }
</style>
@endpush