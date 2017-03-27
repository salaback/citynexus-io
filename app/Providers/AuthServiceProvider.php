<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\DatasetPolicy',
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
        Gate::define('citynexus', function(User $user, $group, $method){
            return $user->allowed($group, $method);
        });
    }

}
