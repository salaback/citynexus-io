<?php

namespace CityNexus\DataStore;

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
        include_once __DIR__ . '/models/Dataset.php';
        include_once __DIR__ . '/models/Uploader.php';
        include_once __DIR__ . '/models/Upload.php';


        // Include Helpers
        include_once __DIR__ . '/helpers/typer.php';
        include_once __DIR__ . '/helpers/DataProcessor.php';
        include_once __DIR__ . '/helpers/DataAccess.php';
        include_once __DIR__ . '/helpers/UploadHelper.php';
        include_once __DIR__ . '/helpers/TableBuilder.php';

        // Inlcude Jobs
        include_once __DIR__ . '/Jobs/ProcessUpload.php';
        include_once __DIR__ . '/Jobs/SaveData.php';
        include_once __DIR__ . '/Jobs/StartImport.php';
        include_once __DIR__ . '/Jobs/ProcessData.php';

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
