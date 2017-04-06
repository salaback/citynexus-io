<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $connection = 'public';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'email', 'super_admin', 'permissions'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts  =
        [
            'memberships' => 'array'
        ];
    public function getFullnameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function allowed($set, $permission)
    {
        $permissions = $this->getGroupPermissions();
        if(isset($permissions[$set][$permission]) && $permissions[$set][$permission]) return true;
        else return false;
    }
    public function disallowed($set, $permission)
    {
        $permissions = $this->getGroupPermissions();
        if(isset($permissions[$set][$permission]) && !$permissions[$set][$permission]) return true;
        else return false;

    }

    public function groups()
    {
        return $this->belongsToMany(UserGroup::class);
    }

    public function getGroupPermissions()
    {
        $groups = $this->groups;

        $permissions = [];

        foreach($groups as $group) $permissions = $this->mergePermissions($group->permissions, $permissions);

        return $permissions;
    }

    private function mergePermissions($new, $old)
    {

        foreach($new as $key => $value)
        {
            foreach($value as $k => $v)
            {
                if(isset($old[$key][$k]))
                {
                    if(!$v)
                    {
                        $old[$key][$k] = false;
                    }
                }
                else
                {
                    $old[$key][$k] = $v;
                }
            }
        }

        return $old;
    }

}