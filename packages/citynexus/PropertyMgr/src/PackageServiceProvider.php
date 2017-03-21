<?php

namespace CityNexus\PropertyMgr;

use Illuminate\Support\ServiceProvider;

class PackageServiceProvider extends ServiceProvider
{

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/migrations/');

        // Include Models
        include_once __DIR__ . '/models/Property.php';
        include_once __DIR__ . '/models/Address.php';
        include_once __DIR__ . '/models/Unit.php';
        include_once __DIR__ . '/models/Entity.php';
        include_once __DIR__ . '/models/RawEntity.php';
        include_once __DIR__ . '/models/Tag.php';
        include_once __DIR__ . '/models/Comment.php';


        // Include Helpers
        include_once __DIR__ . '/helpers/Sync.php';
        include_once __DIR__ . '/helpers/EntitySync.php';
        include_once __DIR__ . '/helpers/Geocode.php';
        include_once __DIR__ . '/helpers/PropertySync.php';

    }


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
        ];
    }
}
