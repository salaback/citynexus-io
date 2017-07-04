<?php

namespace App;

use App\PropertyMgr\Model\Entity;
use App\PropertyMgr\Model\Property;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tag extends Model
{
    protected $table = 'cn_tags';

    public function properties()
    {
        return $this->morphedByMany(Property::class, 'cn_tagable');
    }

    public function entities()
    {
        return $this->morphedByMany(Entity::class, 'cn_tagable');
    }
}
