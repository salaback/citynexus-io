<?php

namespace CityNexus\AnalysisMgr;

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
        include_once __DIR__ . '/models/Score.php';
        include_once __DIR__ . '/models/ScoreResult.php';
        include_once __DIR__ . '/models/CalculatedValue.php';

        // Include Jobs
        include_once __DIR__ . '/jobs/CalculateValue.php';
        include_once __DIR__ . '/jobs/StoreCalculatedValue.php';

        // Include Helpers
        include_once __DIR__ . '/helpers/ScoreBuilder.php';
        include_once __DIR__ . '/helpers/MapHelper.php';
        include_once __DIR__ . '/helpers/Calculator.php';


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
            '\Maatwebsite\Excel\ExcelServiceProvider::class,'
        ];
    }
}
