<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Terms extends Model
{
    protected $fillable = ['terms', 'adopted_at'];

    protected $connection = 'public';
}
