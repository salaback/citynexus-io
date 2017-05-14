<?php


namespace CityNexus\DataStore;

use CityNexus\PropertyMgr\PropertySync;
use Illuminate\Support\Facades\DB;

class DataProcessor
{
    public function __construct()
    {
        $this->sync = new PropertySync();
    }

    public function processData($data , Uploader $uploader)
    {
        $data = $this->applyFilters($data, $uploader);
        if($uploader->hasSyncClass('address')){
            $data = $this->sync->addPropertyID($data, $uploader->getSyncClass('address'));
        }
        return $data;
    }

    private function applyFilters($data, $uploader)
    {
        // Get array of filters grouped by key they relate to
        $filters = $uploader->filters;

        // loop through each set of filters
        foreach($data as $row => $element)
        {
            // loop through each key in the dataset
            foreach ($element as $key => $value)
            {
                // if the filter exists for the key apply each of the filters
                if(isset($filters[$key]))
                {
                    // loop though each of the related filters
                    foreach($filters[$key] as $filter)
                    {
                        // replace the old value with the filtered value
                        $data[$row][$key] = $this->filter($filter, $value);
                    }
                }
            }

        }

        return $data;
    }

    private function filter($filter, $value)
    {
        switch($filter['type'])
        {
            case 'searchReplace':
                return str_replace($filter['needle'], $filter['replace'], $value);
                break;

            default: return $value;
        }
    }


}