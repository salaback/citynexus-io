<?php

namespace App\PropertyMgr\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'comment',
        'posted_by',
        'reply_to',
        'edits',
        'cn_commentable_id',
        'cn_commentable_type',
    ];

    protected $casts = [
      'edits'
    ];

    protected $table = 'cn_comments';

    public function poster()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'cn_commentable');
    }

    public function replyTo()
    {
        return $this->belongsTo(Comment::class, 'reply_to');
    }

    public function commentable()
    {
        return $this->morphTo('cn_commentable');
    }
}
