<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 3/27/17
 * Time: 6:54 PM
 */

namespace CityNexus\DataStore;


use CityNexus\CityNexus\Table;
use CityNexus\PropertyMgr\Property;
use Illuminate\Support\Facades\DB;

class DataAccess
{
    public function getDataByPropertyID($id)
    {
        // Get property model
        $property = Property::find($id);


        // Set id of building
        $ids[] = intval($id);

        // get all IDs of all units
        if($property->units->count())
        {
            foreach($property->units as $unit) $ids[] = $unit->id ;
        }

        $return = [];

        // get each table
        $tables = Table::all();

        // get data for each unit
        foreach($tables as $table)
        {
            $reply = $this->getData($table->table_name, $ids);

            if($reply->count() != 0)
            {
                $return[$table->table_title] = $reply;
            }

        }

        return $return;

    }

    public function getData($table, $ids)
    {
        $results = DB::table($table)
            ->whereIn('property_id', $ids)
            ->get();

        return $results;
    }
}