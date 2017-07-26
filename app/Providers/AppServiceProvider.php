<?php

namespace App\Providers;

use App\AnalysisMgr\Model\Score;
use App\AnalysisMgr\Observers\ScoreObserver;
use App\DataStore\Model\DataSet;
use App\DataStore\Observers\DataSetObserver;
use App\PropertyMgr\Observers\PropertyObserver;
use App\PropertyMgr\Model\Property;
use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\DuskServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        DataSet::observe(DataSetObserver::class);
        Property::observe(PropertyObserver::class);
        Score::observe(ScoreObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local', 'testing')) {
            $this->app->register(DuskServiceProvider::class);
        }
    }
}
