<?php

namespace CityNexus\PropertyMgr;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'cn_tags';
    protected $fillable = ['tag'];

    public $timestamps = false;

    public function properties()
    {
        return $this->belongsToMany('\CityNexus\PropertyMgr\Property', 'property_tag')->whereNull('property_tag.deleted_at')->withPivot('created_at', 'created_by')->orderBy('property_tag.created_at', 'desc');
    }
}
