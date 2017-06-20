<?php


namespace App\DataStore;

use App\DataStore\Model\Upload;
use App\DataStore\Model\Uploader;
use App\PropertyMgr\PropertySync;
use Illuminate\Support\Facades\DB;

class DataProcessor
{
    protected $sync;
    public function __construct()
    {
        $this->sync = new PropertySync();
    }

    public function processData($data , Uploader $uploader)
    {

        $sync = new PropertySync();

        $data = $this->applyFilters($data, $uploader);

        if($uploader->hasSyncClass('address')){

            $data = $sync->addPropertyID($data, $uploader->getSyncClass('address'));
        }

        $data = $sync->addCreatedAt($data, $uploader->getSyncClass('address'));

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