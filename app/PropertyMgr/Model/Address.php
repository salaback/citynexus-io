<?php

namespace App\PropertyMgr\Model;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'building',
        'house_num',
        'predir',
        'qual',
        'pretype',
        'name',
        'suftype',
        'sufdir',
        'ruralroute',
        'extra',
        'city',
        'state',
        'country',
        'postcode',
        'box',
        'unit',
        'property_id',
        'lot_id'
    ];

    protected $table = 'cn_addresses';

    public $timestamps = false;
}
