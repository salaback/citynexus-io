<?php

namespace App;

use CityNexus\CityNexus\Widget;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, SoftDeletes;

    /**
     * The database  used by the model.
     *
     * @var string
     */
    protected $connection = 'public';


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'email', 'super_admin', 'permissions', 'title', 'department'];

    protected $casts  =
        [
            'memberships' => 'array'
        ];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function fullname()
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
        if(!isset($permissions[$set][$permission]) && !$permissions[$set][$permission]) return true;
        else return false;

    }

    public function getWidgetsAttribute()
    {
        if($this->dashboard)
        {
            $widgets = json_decode($this->dashboard);
        }
        else
        {
            $widgets = json_decode(setting('globalDashboard'));
        }

        $widgets = Widget::findMany($widgets);
        return $widgets;
    }

    public function api()
    {
        return $this->hasOne('\CityNexus\CityNexus\APISecret');
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
