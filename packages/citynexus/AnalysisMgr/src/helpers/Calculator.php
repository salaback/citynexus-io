<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 4/20/17
 * Time: 7:18 AM
 */

namespace CityNexus\AnalysisMgr;


use App\Jobs\StoreCalculateValue;
use CityNexus\DataStore\DataSet;
use CityNexus\DataStore\TableBuilder;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Oefenweb\Statistics\Statistics;

class Calculator
{
    use DispatchesJobs;

    public function calculateValue($id)
    {
        $calculated_value = CalculatedValue::find($id);

        switch ($calculated_value->type)
        {
            case 'zscore':
                $this->zscore($calculated_value);
                break;
            case 'log':
                $this->log($calculated_value);
                break;

        }

        $calculated_value->touch();
    }

    public function addNewValue($key, $type)
    {
        switch ($type)
        {
            case 'tvalue':
                $field = 'float';
                break;
            default:
                $field = 'string';
        }

        Schema::table('cn_values', function (Blueprint $table) use($key, $field){
            $table->$field($key)->nullable();
        });
    }

    private function log(CalculatedValue $calculatedValue)
    {
        $field = $calculatedValue->settings['field'];
        // Get array of values

        $dataset = DataSet::find($calculatedValue->settings['table']);

        $data = $this->getDataPoint($dataset->table_name, $field);

        foreach($data as $item)
        {
            $results[$item->property_id] = preg_replace("/[^0-9]/","",$item->$field);
        }

        $calculatedItems = [];
        if(count($results) > 0)
        {
            foreach($results as $key => $value)
            {
                $item = [];
                $item['property_id'] = $key;
                $item['value'] = log($value);
                $calculatedItems[$key] = $item;
            }

            $this->queueStorage($calculatedItems, 'property', $calculatedValue->key);
        }

    }

    private function zscore(CalculatedValue $calculatedValue)
    {

        $field = $calculatedValue->settings['field'];
        // Get array of values

        $dataset = DataSet::find($calculatedValue->settings['table']);

        $data = $this->getDataPoint($dataset->table_name, $field);

        $results = [];

        foreach($data as $item)
        {
            $results[$item->property_id] = preg_replace("/[^0-9]/","",$item->$field);
        }

        // Get standard div

        $stdDiv = Statistics::standardDeviation($results);
        $mean = Statistics::mean($results);

        if(count($results) > 0) {
            $calculatedItems = [];

            foreach ($results as $key => $value) {
                $item = [];
                $item['property_id'] = $key;
                $item['value'] = ($value - $mean) / $stdDiv;
                $calculatedItems[$key] = $item;
            }

            // Store array of ids and values
            $this->queueStorage($calculatedItems, 'property', $calculatedValue->key);
        }

    }

    private function queueStorage($data, $type, $key)
    {
        $chunks = array_chunk($data, 100);

        foreach($chunks as $chunk)
        {
            $this->dispatch(new StoreCalculateValue(config('client.id'), $type, $key, $chunk));
        }
    }

    private function getDataPoint($table, $field)
    {
        return DB::table($table)->get([$field, 'property_id', 'entity_id']);
    }

    public function storeValueData($type, $field, $data)
    {
        switch ($type)
        {
            case 'property':
                foreach($data as $item)
                {

                    // If a matching value row exists for property id
                    if(DB::table('cn_values')->where('property_id', $item['property_id'])->count() > 0)
                    {
                        // update with new value
                        DB::table("cn_values")->where(['property_id' => $item['property_id']])->update([$field => $item['value']]);
                    }
                    else
                    {
                        // else create new row
                        DB::table("cn_values")->insert(['property_id' => $item['property_id'], $field => $item['value']]);
                    }

                }
        }
    }


}