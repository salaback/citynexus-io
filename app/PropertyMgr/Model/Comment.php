<?php

namespace App\PropertyMgr\Model;

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
        return $this->belongsTo('\App\User', 'posted_by');
    }

    public function comments()
    {
        return $this->morphMany('App\PropertyMgr\Model\Comment', 'cn_commentable');
    }

    public function replyTo()
    {
        return $this->belongsTo('App\PropertyMgr\Model\Comment', 'reply_to');
    }

}
