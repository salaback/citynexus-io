<?php

namespace CityNexus\PropertyMgr;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Phaza\LaravelPostgis\Eloquent\PostgisTrait;

class Property extends Model
{
    use SoftDeletes;
    use PostgisTrait;

    protected $fillable = [
        'building_id',
        'lot_id',
        'address',
        'unit',
        'city',
        'state',
        'postcode',
        'country',
        'is_building',
        'is_unit',
        'is_lot',
        'point',
        'polygon'
        ];

    protected $postgisFields = [
        'location',
        'polygon'
    ];


    protected $table = 'cn_properties';

    public function getOneLineAddressAttribute()
    {
        return trim($this->address . ' ' . $this->unit);
    }

    public function building()
    {
        return $this->belongsTo('\CityNexus\PropertyMgr\Property', 'building_id');
    }

    public function getFullAddressAttribute()
    {
        return $this->address . ' ' . $this->city . ', ' . $this->state . ' ' . $this->postcode;
    }

    public function units()
    {
        return $this->hasMany('\CityNexus\PropertyMgr\Property', 'building_id');
    }

    public function entities()
    {
        return $this->morphToMany('CityNexus\PropertyMgr\Entity', 'entitables', 'cn_entitables')->withPivot('role');
    }

    public function tags()
    {
        return $this->morphToMany('CityNexus\PropertyMgr\Tag', 'tagables', 'cn_tagables')->withPivot('created_by', 'created_at', 'deleted_by', 'deleted_at');
    }

    public function trashedTags()
    {
        return $this->belongsToMany('\CityNexus\CityNexus\Tag', 'property_tag')->whereNotNull('property_tag.deleted_at')->orderBy('property_tag.deleted_at', 'desc')->withTimestamps()->withPivot('created_by', 'deleted_by', 'deleted_at');
    }

    public function comments()
    {
        return $this->morphMany('CityNexus\PropertyMgr\Comment', 'cn_commentable');
    }

    public function files()
    {
        return $this->hasMany('CityNexus\CityNexus\File');
    }

    public function notes()
    {
        return $this->hasMany('CityNexus\CityNexus\Note');
    }

    public function tasks()
    {
        return $this->morphToMany('\CityNexus\CityNexus\Task', 'citynexus_taskable');
    }

}
