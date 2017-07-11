<?php

namespace App\PropertyMgr\Model;

use Illuminate\Database\Eloquent\Model;

class EntityAddress extends Model
{
    protected $table = 'cn_entity_addresses';

    protected $fillable = ['address', 'city', 'state', 'postcode'];
}
