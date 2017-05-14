<?php

namespace CityNexus\PropertyMgr;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'unit',
        'unit_type',
        'address_id',
        'postal_code',
        'property_id',
        'lot_id'
    ];

    protected $table = 'cn_units';

    public $timestamps = false;

}
