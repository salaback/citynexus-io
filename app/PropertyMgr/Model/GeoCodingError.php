<?php

namespace App\PropertyMgr\Model;

use Illuminate\Database\Eloquent\Model;

class GeoCodingError extends Model
{
    protected $fillable = ['error', 'model_id', 'model_type', 'address'];


}
