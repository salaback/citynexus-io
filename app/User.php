<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;

    protected $connection = 'public';

    protected $table = 'public.users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'email', 'super_admin', 'permissions', 'password'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = [
        'accepted_terms',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts  =
        [
            'memberships' => 'array',
            'terms' => 'array'
        ];
    public function getFullnameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Returns a true or false for if a user has the permission
     * being tested.
     *
     * @param $set
     * @param $permission
     * @return bool
     */
    public function allowed($set, $permission)
    {
        $permissions = $this->getGroupPermissions();
        if(isset($permissions[$set][$permission]) && $permissions[$set][$permission]) return true;
        else return false;
    }

    /**
     *
     * Returns true or false based on the user
     * not having a permission.
     *
     * @param $set
     * @param $permission
     * @return bool
     */
    public function disallowed($set, $permission)
    {
        $permissions = $this->getGroupPermissions();
        if(isset($permissions[$set][$permission]) && !$permissions[$set][$permission]) return true;
        else return false;
    }

    /**
     *
     * All groups user is a member of
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(UserGroup::class);
    }

    /**
     *
     * Array of all the permissions belonging to
     * the groups a user is a member of
     *
     * @return array
     */
    public function getGroupPermissions()
    {
        $groups = $this->groups;

        $permissions = [];

        foreach($groups as $group) $permissions = $this->mergePermissions($group->permissions, $permissions);

        return $permissions;
    }

    public function getInfoAttribute()
    {
        $info = new \stdClass();

        if(isset($this->memberships[config('domain')]['title']))
            $info->title = $this->memberships[config('domain')]['title'];
        else
            $info->title = null;

        if(isset($this->memberships[config('domain')]['department']))
            $info->department = $this->memberships[config('domain')]['department'];
        else
            $info->department = null;

        return $info;
    }

    public function addMembership($domain, $options = array(), $force = false)
    {
        $memberships = $this->memberships;
        if(!$force && isset($memberships[$domain]))
            App::abort(500, "Membership already exists");

        else
        {
            $memberships[$domain] = $options;
            $this->memberships = $memberships;
            $this->save();
            return 'added';
        }

    }
    /**
     *
     * Add a new membership array to the memberships
     *
     * @param $memberships
     */
    public function addMemberships($memberships, $force = false)
    {
        foreach($memberships as $key => $value)
        {
            $this->addMembership($key, $value, $force);
        }

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

    public static function fromClient()
    {

        $users = [];
        foreach(User::all() as $user)
        {
            $memberships = $user->memberships;
            if(isset($memberships[config('domain')]))
            {
                $users[] = $user->id;
            }
        }

        return User::findMany($users);
    }

    public function isMember(UserGroup $userGroup)
    {
        if(DB::table('user_user_group')->where('user_id', $this->id)->where('user_group_id', $userGroup->id)->count() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

}