<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 4/6/17
 * Time: 1:48 PM
 */

namespace App\AnalysisMgr;


use App\DataStore\Model\DataSet;
use App\PropertyMgr\Model\Property;
use Illuminate\Support\Facades\DB;

class MapHelper
{
    public function createDatapoint($dataset_id, $key)
    {
        $dataset = DataSet::find($dataset_id);

        $results = DB::table($dataset->table_name)
            ->where($key, '>', '0')
            ->orderBy($dataset->table_name . '.__created_at')
            ->join('cn_properties', $dataset->table_name . '.__property_id', '=', 'cn_properties.id')
            ->select($dataset->table_name . '.' . $key, 'cn_properties.id', 'cn_properties.address', 'cn_properties.unit', 'cn_properties.cords')
            ->get();

        $max = 0;

        $points = [];

        foreach($results as $i)
        {
            if($i->cords != null)
            {
                $cords = json_decode($i->cords, true);
                $address = $i->address;
                if($i->unit != null) $address .= ' #' . $i->unit;
                $points[$i->id] = [
                    'name' => ucwords($address),
                    'value' => $i->$key,
                    'url' => route('properties.show', [$i->id]),
                    'lat' => $cords['lat'],
                    'lng' => $cords['lng'],
                ];
            }
            if($max < $i->$key) $max = $i->$key;
        }

        $points = $this->createInfoFlags($points);

        $return['points'] = array_values($points);
        $return['max'] = $max * 1.1;
        $return['title'] = $dataset->name . " > " . $dataset->schema[$key]['name'];
        $return['handle'] = $dataset->id . '_' . $key;

        return $return;
    }


    private function createInfoFlags($points)
    {
        $return = [];

        foreach($points as $point)
        {
            if(isset($return[$point['lat'] . '-' . $point['lng']]['value']))
            {
                $return[$point['lat'] . '-' . $point['lng']]['value'] = ($return[$point['lat'] . '-' . $point['lng']]['value'] / $return[$point['lat'] . '-' . $point['lng']]['count']) + ($point['value'] / $return[$point['lat'] . '-' . $point['lng']]['count']) ;
                $return[$point['lat'] . '-' . $point['lng']]['message'] = $return[$point['lat'] . '-' . $point['lng']]['message'] . '(' . $point['value'] . ')' . ' - <a href="' . $point['url'] . '" target="_blank">' . $point['name'] . '</a></br>';
                $return[$point['lat'] . '-' . $point['lng']]['count'] = $return[$point['lat'] . '-' . $point['lng']]['count'] + 1;
            }
            else
            {
                $return[$point['lat'] . '-' . $point['lng']]['value'] = $point['value'];
                $return[$point['lat'] . '-' . $point['lng']]['count'] = 1;
                $return[$point['lat'] . '-' . $point['lng']]['lat'] = $point['lat'];
                $return[$point['lat'] . '-' . $point['lng']]['lng'] = $point['lng'];
                $return[$point['lat'] . '-' . $point['lng']]['message'] = '(' . $point['value'] . ')' . ' - <a href="' . $point['url'] . '" target="_blank">' . $point['name'] . '</a></br>';
            }
        }

        return $return;
    }

    public function getFromRange($ids = array(), $range)
    {
        $properties = Property::findMany($ids);
        $return = [];
        DB::statement('SET search_path TO ' . config('schema') . ',public');

        foreach($properties as $property)
        {
            $results = DB::table('cn_properties')
                ->where(DB::raw('ST_Distance(cn_properties.location, ST_makepoint(' . $property->location->getLng() . ', ' . $property->location->getLat() . '))'), '<=', $range)
                ->get()
                ->toArray();

            $return = array_merge($return, $results);
        }

        return $return;
    }
}