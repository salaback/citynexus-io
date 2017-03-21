<?php

namespace CityNexus\PropertyMgr;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
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

    public function commentable()
    {
        return $this->morphTo();
    }

    public function comments()
    {
        return $this->morphMany('CityNexus\PropertyMgr\Comment', 'cn_commentable');
    }

}
