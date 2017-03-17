<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $connection = 'public';

    protected $fillable = ['name', 'domain', 'schema', 'migrated_at','active', 'settings'];

    protected $casts = [
        'active' => 'boolean',
        'settings' => 'array',
        'info' => 'array'
    ];

    protected $dates = ['migrated_at', 'created_at', 'updated_at'];


    public function version()
    {
        return $this->belongsTo(Version::class);
    }
}
