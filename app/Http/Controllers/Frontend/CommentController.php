<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Notifications\ReplyToComment;
use App\PropertyMgr\Model\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'max:255',
            'comment' => 'required'
        ]);

        $comment = Comment::create($request->all());

        if($comment->reply_to != null)
        {
            $type = $comment->cn_commentable_type;
            $commentable_id = $comment->cn_commentable_id;
            $comment->cn_commentable_id = $comment->reply_to;
            $comment->cn_commentable_type = 'App\PropertyMgr\Model\Comment';
            $comment->replyTo->poster->notify(new ReplyToComment($comment, $type, $commentable_id));
            $comment->save();
        }

        return view('property.snipits._comment', compact('comment'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $comment = $this->findComment(Comment::find($id));

        switch ($comment->cn_commentable_type)
        {
            case 'App\PropertyMgr\Model\Property':
                return redirect(route('properties.show', [$comment->commentable_id]) . '?tab=comments#comment-' . $comment->id);

        }
    }

    private function findComment(Comment $comment)
    {
        if($comment->cn_commentable_type == 'App\PropertyMgr\Model\Comment')
        {
            return $this->findComment(Comment::find($comment->reply_to));
        }
        else
            return $comment;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function search(Request $request)
    {

        $query = "%" . $request->get('query') . "%";
        $comments = Comment::where('comment', 'LIKE', $query)->get();

        if($request->exists('range'))
        {
            $ids = $comments->get('id')->toArray();
        }

        return $comments;
    }
}
