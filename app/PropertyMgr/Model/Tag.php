<?php

namespace App\PropertyMgr\Model;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'cn_tags';
    protected $fillable = ['tag'];

    public $timestamps = false;

    public function properties()
    {
        return $this->belongsToMany('App\PropertyMgr\Model\Property', 'property_tag')->whereNull('property_tag.deleted_at')->withPivot('created_at', 'created_by')->orderBy('property_tag.created_at', 'desc');
    }
}
