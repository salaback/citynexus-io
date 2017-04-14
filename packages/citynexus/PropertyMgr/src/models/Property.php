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
        return $this->belongsTo('\CityNexus\PropertyMgr\Property', 'building_id');
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

    public function comments()
    {
        return $this->morphMany('CityNexus\PropertyMgr\Comment', 'cn_commentable');
    }

    public function files()
    {
        return $this->morphMany('CityNexus\PropertyMgr\File', 'cn_fileable');
    }

}
