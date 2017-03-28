<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    protected $fillable = ['name', 'permissions'];
    protected $casts = [
        'permissions' => 'array'
    ];
    protected $connection = 'public';

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
