<?php

namespace App\DataStore\Model;

use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
    protected $table = 'cn_connections';

    protected $casts = [
        'settings' => 'array'
    ];

    protected $fillable = ['type', 'settings', 'created_by'];
}
