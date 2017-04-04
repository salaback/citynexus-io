<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 4/3/17
 * Time: 9:36 PM
 */

namespace CityNexus\DataStore\Helper;


use Carbon\Carbon;
use CityNexus\DataStore\DataProcessor;
use CityNexus\DataStore\TableBuilder;
use CityNexus\DataStore\Upload;
use CityNexus\DataStore\Uploader as UploaderModal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Uploader
{

    public function __construct()
    {
        $this->dataProcessor = new DataProcessor();
        $this->tableBuilder = new TableBuilder();
    }
    /**
     *
     * Generic data upload feature requires an uploader model and an array of data
     *
     * @param $uploader
     * @param $data
     *
     * @return boolean
     */
    public function importData($data, UploaderModal $uploader)
    {
        // check for dataset updated table
        $this->tableBuilder->syncTable($uploader->dataset);

        // Format data to map
        $data = $this->mapData($data, $uploader->map);

        // Process data
        $data = $this->dataProcessor->processData($data, $uploader);

        // Create and add Upload id
        $upload = Upload::create([
            'uploader_id' => $uploader->id,
            'source' => 'sql_import',
            'file_type' => 'sql_import',
            'size' => count($data),
            'processed_at' => Carbon::now(),
            'user_id' => Auth::id(),
        ]);

        foreach($data as $key => $item)
        {
            $data[$key]['upload_id'] = $upload->id;
        }

        // Save data
        DB::table($uploader->dataset->table_name)->insert($data);

        return count($data);
    }

    /**
     * @param $source
     * @param $uploader
     */
    public function sqlUpload($source, $uploader)
    {
        // check for dataset updated table
        $this->tableBuilder->syncTable($uploader->dataset);

        config(['database.connections.source' => $source]);
        $data = DB::table($uploader->settings['table_name'])->get();

        $chunks = $data->chunk(100);
        foreach($chunks as $chunk)
        {
            $this->importData($chunk, $uploader);
        }
    }

    /**
     *
     * Use array of data map to return a premapped dataset
     *
     * @param $map
     * @param $data
     */
    public function mapData($data, $map)
    {
        $return = [];
        foreach($data as $item)
        {
            $newItem = [];
            foreach ($item as $key => $value)
            {
                if(isset($map[$key])) $newItem[$map[$key]] = $value;
            }
            $return[] = $newItem;
        }

        return $return;
    }

    public function checkTable($table_name, $schema)
    {

        foreach($schema as $key => $item)
        {
           if(!Schema::hasColumn($table_name, $key))
           {
               return false;

           }
        }
        return true;
    }

}