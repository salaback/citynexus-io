<?php

namespace App\PropertyMgr\Model;

use App\DataStore\Model\DataSet;
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
        'cords',
        'state',
        'postcode',
        'country',
        'is_building',
        'is_unit',
        'is_lot',
        'location',
        'polygon'
    ];

    protected $postgisFields = [
        'location',
        'polygon'
    ];

    protected $casts = [
        'cords' => 'array'
    ];

    protected $table = 'cn_properties';

    public function getOneLineAddressAttribute()
    {
        if($this->unit == null)
            return $this->address;
        else
            return $this->address . ' #' . $this->unit;
    }

    public function building()
    {
        return $this->belongsTo('App\PropertyMgr\Model\Property', 'building_id');
    }

    public function getFullAddressAttribute()
    {
        $address = $this->address;

        if($this->city != null) {$address .= ', ' . $this->city;}
        else {$address .= ', ' . config('client.city');}

        if($this->state != null) { $address .= ', ' . $this->state;}
        else {$address .= ', ' . config('client.state');}

        if($this->postal_code != null) { $address .= ' ' . $this->postal_code;}

        return $address;
    }

    public function units()
    {
        return $this->hasMany('App\PropertyMgr\Model\Property', 'building_id');
    }

    public function entities()
    {
        return $this->morphToMany('App\PropertyMgr\Model\Entity', 'entitables', 'cn_entitables')->withPivot('role');
    }

    public function tags()
    {
        return $this->morphToMany('App\PropertyMgr\Model\Tag', 'tagables', 'cn_tagables')->whereNull('cn_tagables.deleted_at')->orderBy('cn_tagables.created_at')->withPivot('created_by', 'created_at', 'id');
    }

    public function trashedTags()
    {
        return $this->morphToMany('App\PropertyMgr\Model\Tag', 'tagables', 'cn_tagables')->whereNotNull('cn_tagables.deleted_at')->orderBy('cn_tagables.deleted_at')->withPivot('deleted_at', 'deleted_by', 'created_by', 'created_at', 'id');
    }

    public function comments()
    {
        return $this->morphMany('App\PropertyMgr\Model\Comment', 'cn_commentable');
    }

    public function files()
    {
        return $this->morphMany('App\PropertyMgr\Model\File', 'cn_fileable');
    }

    public function getPointAttribute()
    {
        if($this->is_unit)
        {
           return $this->building->location;
        }
        else
        {
            return $this->location;
        }
    }
}
