<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 3/11/17
 * Time: 6:46 AM
 */

namespace App\Services;


use App\Jobs\ImportDb;
use CityNexus\CityNexus\Property;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\DB;

class DbImport
{

    use DispatchesJobs;

    public function importProperties($source, $target_schema)
    {

        set_time_limit(0);

        $properties = new Property();
        $properties->setConnection('import');

        $old_properties = $properties->get();

        DB::statement("SET search_path TO " . config('database.connections.tenant.schema') . ',public');

        foreach($old_properties as $property)
        {
            $this->dispatch(new ImportDb($property->id, $source, $target_schema));
        }

        config(['database.default' => 'public']);

    }
}