<?php

namespace App\PropertyMgr\Model;

use Illuminate\Database\Eloquent\Model;
use Phaza\LaravelPostgis\Eloquent\PostgisTrait;

class Shape extends Model
{
    use PostgisTrait;

    protected $fillable = ['name', 'polygon', 'point'];

    protected $postgisFields = [
        'location',
        'polygon'
    ];
}
