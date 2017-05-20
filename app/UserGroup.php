<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserGroup extends Model
{
    protected $fillable = ['name', 'permissions'];
    protected $casts = [
        'permissions' => 'array'
    ];
    protected $connection = 'tenant';

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function getUserCountAttribute()
    {
        return DB::table('user_user_group')->where('user_group_id', $this->id)->count();
    }
}
