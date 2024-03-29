<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 11/2/16
 * Time: 10:33 PM
 */

namespace App\DataStore;

use App\DataStore\Model\DataSet;
use App\DataStore\Model\Upload;
use App\DataStore\Model\Uploader;
use Carbon\Carbon;
use App\PropertyMgr\Model\Property;
use App\PropertyMgr\Sync;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\DataStore\Typer;



class Store extends DataProcessor
{

    public function __construct()
    {
        $this->typer = new Typer();
        $this->process = new DataProcessor();
        $this->sync = new Sync();
    }

    /**
     * @param $ids
     * @return array
     */
    public function getDatasetsForMany($ids)
    {

        // load the dataset results for each property ID
        $results = [];

        foreach($ids as $id) $results[] = $this->getDatasets($id);


        // merge the results together
        $return = [];
        foreach($results as $result)
        {
            foreach($result as $key => $set)
            {
                if(isset($return[$key]))
                {
                    $return[$key] = array_merge($return[$key]['data'], $set['data']);
                }
                else
                {
                    $return[$key] = $set;
                }
            }
        }

        return $return;
    }


    public function getDatasets($id)
    {

        $tables = DataSet::whereNotNull('table_name')->get();

        $property = Property::find($id);

        $query = 'property_id=' . $id;

        foreach($property->units as $unit) $query .= ' OR property_id=' . $unit->id;

        $datasets = [];
        foreach ($tables as $i)
        {
            if(Schema::hasTable($i->table_name))
            {
                $data = DB::table($i->table_name)->whereRaw(DB::raw($query))->get();
                if($data != null){
                    $datasets[$i->id]['data'] = $data;
                    $datasets[$i->id]['model'] = $i;
                }
            }

        }

        return (object) $datasets;
    }

    /**
     * Create a new uploader
     *
     * @param $settings
     * @return mixed
     */

    public function analyzeFile ($source, $type, $is_local = false)
    {

        if(!$is_local)
        {
            $source = $this->localFile($source);
        }

        set_time_limit(0);

        if($type == 'text/csv')
        {

            $data = Excel::load($source, function($reader){
                $reader->takeRows(50);
            })->get();

            $data = $data->toArray();

        }
        elseif(str_contains($type, 'spreadsheetml'))
        {

            config(['excel.import.force_sheets_collection' => true]);

            $data = Excel::load($source, function($reader){
                $reader->takeRows(50);
            })->get();

            $data = $data->first()->toArray();
        }

        else
        {
            throw new \Error('File type not accepted');
        }

        unlink($source);

        return $this->analyzeData($data);

    }

    public function analyzeData($data)
    {

        $results = [];
        foreach ($data as $i) {
            foreach ($i as $key => $field)
            {
                // if first time touched
                if (!isset($results[$key]) && $field != null) {
                    $results[$key] = [
                        'key' => $this->keyer($key),
                        'name' => $this->namer($key),
                        'value' => trim($field),
                        'type' => null
                    ];
                }
                if ($field != null) $results[$key]['type'] = $this->testField($results[$key]['type'], $field);
                // If more that 100 records have been tested, stop.
            }
        }

        return $results;
    }

    public function analyzeSQL($settings)
    {
        config(['database.connections.target' => array_filter($settings['db'])]);

        $fields = DB::connection('target')
            ->table('information_schema.columns')
            ->where('table_schema', $settings['db']['schema'])
            ->where('table_name', $settings['table'])
            ->get();

        $values = (array) DB::connection('target')
            ->table($settings['table'])
            ->first();

        $results = [];

        foreach($fields as $i)
        {
            $results[$i->column_name] = [
                'key' => $i->column_name,
                'name' => $this->namer($i->column_name),
                'type' => $i->data_type,
                'value' => $values[$i->column_name]
            ];
        }

        return $results;
    }


    public function openFile ($path, $isLocal = false)
    {
        if($isLocal)
        {
            $source = $path;
        }
        else
        {
            $source = $this->localFile($path);
        }

        // Load Data
        $data = Excel::load($source, function($reader){$reader->all();});

        // If multiple sheets
        if($this->excelSheets($data))  {
            $data = $data->parsed->first();
        }

        return $data;
    }

    public function localFile($source)
    {
        $extension = explode('.', $source);
        $extension = end($extension);
        $tempname = storage_path() . "/tmpfile_"  . date('hms') . "." . $extension;
        file_put_contents($tempname, Storage::disk('s3')->get($source));
        return $tempname;
    }

    /**
     *
     * Test if the file has multiple excel spreadsheets
     *
     * @param $data
     * @return array|bool
     */
    private function excelSheets($data)
    {
        $sheets = [];

        foreach($data->parsed as $i)
        {

            if($i->getTitle() != null)
            {
                $sheets[] = $i->getTitle();
            }
        }

        if(count($sheets) > 0)
        {
            return $sheets;
        }

        return false;
    }

    private function testFields($data)
    {
        // open blank results array
        $results = [];

        foreach ($data as $i)
        {
            foreach ($i as $key => $field)
                if($field != null) $results[$key] = $this->testField($field['type'], $field);
        }

        return $results;
    }

    private function testField($type, $value)
    {
        // if no type has been set in the results return typer's result
        if($type == null) return $this->typer->type($value);

        // if a type is set, check if it works with the next field
        switch ($this->typer->type($value))
        {
            case 'string':
                if($type == 'text')
                    return 'text';
                else
                    return 'string';

            case 'datetime':
                if($type != 'datetime')
                    return 'string';
                else
                    return 'datetime';

            case 'integer':
                if($type == 'integer')
                    return 'integer';
                elseif($type == 'float')
                    return 'float';
                else
                    return 'string';

            case 'float':
                if($type == 'integer' || $type == 'float')
                    return 'float';
                else
                    return 'string';

            case 'boolean':
                if($type == 'boolean')
                    return 'boolean';
                else
                    return 'string';

            case 'text':
                return 'text';
        }
    }

    public function keyer($key)
    {
        $return = preg_replace("/[^a-zA-Z0-9_ -%][().'!][\/]/s", '', $key);
        $return = strtolower($return);
        $return = str_replace(["'", "`", "!"], '',$return);
        $return = str_replace(["/", " ", "-"], '_',$return);
        return $return;
    }

    /**
     * @return string
     */
    public function tableNameMaker($name)
    {
        $table_name = 'data_' . random_int(1000, 9999) . '_' . $this->keyer($name);

        // check if table already exists or else choose new random int.
        while(Schema::hasTable($table_name))
        {
            $table_name = 'data_' . random_int(1000, 9999) . '_' . $this->keyer($name);
        }

        // return tested table name
        return $table_name;
    }

    private function namer($key)
    {
        return ucwords($key);
    }

    public function createTable($id)
    {
        $dataset = DataSet::find($id);

        $fields = $dataset->schema;

        if($dataset->table_name == null)
        {
            $dataset->table_name = $this->tableNameMaker($dataset->table_name);
            $dataset->save();
        }

        DB::statement("SET search_path TO " . config('schema'));

        if(!Schema::hasTable($dataset->table_name)) {
            Schema::create($dataset->table_name, function (Blueprint $table) use ($fields) {
                // Create table's index id file
                $table->increments('id');
                $table->integer('upload_id')->unsigned();
                $table->integer('property_id')->nullable()->unsigned();
                foreach ($fields as $field) {
                    $type = $field['type'];
                    if($field['key'] == 'id') {$field['key'] = $field['key'] . '-original';}
                    $table->$type($field['key'])->nullable();
                }
                $table->timestamps();
            });
        }

        return $dataset->table_name;
    }

    public function processSQL(Upload $upload)
    {
        // load source db
        config(['database.connections.target' => $upload->uploader->settings['db']]);

        switch ($upload->settings['scope'])
        {
            case 'all':
                $data = DB::connection('target')->table($upload->uploader->settings['table'])->pluck($upload->uploader->settings['unique_id']);

                foreach($data->chunk(200) as $i)
                {
                    dispatch(new Jobs\ProcessData($i, $upload->id));
                }
        }


    }

    public function prepareDataProcess($upload)
    {
        // load helpers
        $syncHelper = new Sync();
        $uploader = Uploader::find($upload->uploader_id);

        if($uploader->type == 'sql')
        {
            config(['database.connections.target' => $uploader->settings['db']]);
            $this->data = DB::connection('target')
                ->table($uploader->settings['table'])
                ->whereIn($uploader->settings['unique_id'], $this->data)
                ->get();

            $this->data = collect($this->data)->map(function($x){ return (array) $x; })->toArray();
        }

        $data = $this->processData($this->data , $uploader);

        $final_data = [];

        $map = $uploader->map;

        // load data
        foreach($data as $row)
        {
            $new_row['upload_id'] = $upload->id;

            foreach ($row as $key => $item)
            {
                if(isset($map[$key]['type']) && $map[$key]['key'] != null)
                {
                    $new_row[$key] = $this->cast($item, $map[$key]['type']);
                }
            }
            $final_data[] = $new_row;
        }

        $max_id = DB::table($uploader->dataset->table_name)->max('id');

        DB::table($uploader->dataset->table_name)->insert($final_data);

        if($max_id != null) $data = DB::table($uploader->dataset->table_name)->where('id', '>', $max_id)->get();
        else $data = DB::table($uploader->dataset->table_name)->get();

        $syncHelper->postSync($data, (object) $uploader->syncs, $upload->id);
        $upload->count += $data->count();
        $upload->processed_at = Carbon::now();
        $upload->save();

    }
}