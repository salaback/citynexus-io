<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 6/14/17
 * Time: 6:26 AM
 */

namespace App\DataStore;


use App\AnalysisMgr\Statistics;
use App\DataStore\Model\DataSet;
use Illuminate\Support\Facades\DB;

class DataInformation
{

    public function datapointInfo(DataSet $dataset, $key)
    {
        $data = DB::table($dataset->table_name)->whereNotNull($key)->get(['__id', $key, '__created_at'])->toArray();


        $return['values'] = $this->getValues($data, $key);
        $return['daterange'] = $this->getDateRange($data);
        $return['type'] = $dataset->schema[$key]['type'];
        $return['frequency'] = $this->getFrequency($return['values']);
        if($return['type'] == 'integer' || $return['type'] == 'float'){
            $return['stats'] = $this->getStats($return['values']);
        }

        return $return;
    }

    private function getDateRange($data) {

        $dates = [];

        foreach($data as $item)
        {
            $dates[] = $item->__created_at;
        }

        asort($dates);

        $return['start'] = reset($dates);
        $return['end'] = end($dates);

        return $return;
    }

    private function getFrequency($data)
    {
        $frequency = [];

        foreach($data as $i)
        {
            if(isset($frequency[$i]))
                $frequency[$i] += 1;
            else
                $frequency[$i] = 1;
        }

        asort($frequency);

        return $frequency;
    }

    private function getValues($data, $key)
    {
        $values = [];

        foreach($data as $i)
        {
            $values[] = $i->$key;
        }

        asort($values);

        return $values;
    }

    private function getStats($values)
    {
        $stats = new Statistics();

        $return['min'] = reset($values);
        $return['max'] = end($values);
        $return['count'] = count($values);
        $return['mean'] = $stats->mean($values);
        $return['median'] = $stats->median($values);
        $return['stdDiv'] = round($stats->standard_deviation($values), 4);

        return $return;
    }

}