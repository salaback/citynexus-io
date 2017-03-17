<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    protected $connection = 'public';
    protected $fillable = ['version', 'released_at', 'notes'];
    protected $dates = [
      'updated_at', 'created_at', 'released_at'
    ];

}
