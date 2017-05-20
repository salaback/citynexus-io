<?php

namespace App\Providers;

use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Gate::before(function ($user) {
            if ($user->super_admin) {
                return true;
            }
        });

        // Dataset Permissions
        Gate::define('citynexus', function($user, $group, $method){
            if(isset($user->memberships[config('schema')]['account_owner']))
            {
                return true;
            }
            return $user->allowed($group, $method);
        });

    }
}
