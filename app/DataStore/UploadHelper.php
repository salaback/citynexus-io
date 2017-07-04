<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 4/3/17
 * Time: 9:36 PM
 */

namespace App\DataStore;


use App\DataStore\Jobs\SaveData;
use App\Jobs\ImportProcessData;
use Carbon\Carbon;
use App\DataStore\DataProcessor;
use App\DataStore\Store;
use App\DataStore\TableBuilder;
use App\DataStore\Model\Upload;
use App\DataStore\Model\Uploader;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class UploadHelper
{

    use DispatchesJobs;

    public $tableBuilder;
    public $dataProcessor;
    public $store;

    public function __construct()
    {
        $this->dataProcessor = new DataProcessor();
        $this->tableBuilder = new TableBuilder();
        $this->store = new Store();
    }

    /**
     *
     * Generic data upload feature requires an uploader model and an array of data
     *
     * @param $data
     *
     * @param Uploader $uploader
     * @param $upload_id
     * @return bool
     */
    public function importData($data, Uploader $uploader, $upload_id)
    {
        // check for dataset updated table
        $this->tableBuilder->syncTable($uploader->dataset);

        // Format data to map
        $data = $this->mapData($data, $uploader->map);

        // Process data
        $data = $this->dataProcessor->processData($data, $uploader);


        // Add Upload ID
        $data = $this->addUploadId($data, $upload_id);

        // Save data
        DB::table($uploader->dataset->table_name)->insert($data);


        $upload = Upload::find($upload_id);
        $upload->count += count($data);
        $upload->save();
        return count($data);
    }

    /**
     * @param $source
     * @param $uploader
     */
    public function sqlUpload($uploader, $settings = [])
    {
        Log::info('Got to the start Upload');

        $uploader = Uploader::find($uploader);

        // check for dataset updated table
        $this->tableBuilder->syncTable($uploader->dataset);

        config(['database.connections.source' => $uploader->settings]);

        $data = DB::connection('source')->table($uploader->settings['table_name'])->get();

        $chunks = $data->chunk(100);

        $upload = Upload::create([
            'uploader_id' => $uploader->id,
            'source' => 'sql_import',
            'file_type' => 'sql_import',
            'size' => count($data),
            'processed_at' => Carbon::now(),
            'user_id' => $settings['user_id'],
            'queues' => count($chunks)
        ]);

        foreach($chunks as $chunk)
        {
            dispatch((new SaveData(config('client.id'), $chunk, $uploader->id, $upload->id))->onQueue('low'));
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

    private function addUploadId($data, $upload_id)
    {
        foreach($data as $k => $i)
        {
            $data[$k]['upload_id'] = $upload_id;
        }

        return $data;

    }
}