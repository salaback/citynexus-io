<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 7/8/17
 * Time: 7:11 AM
 */

namespace App\DataStore;


use App\DataStore\Jobs\ImportChunk;
use App\DataStore\Jobs\ImportFromCsv;
use App\DataStore\Model\Upload;
use App\PropertyMgr\Sync;
use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class Importer
{
    public function fromUpload(Upload $upload)
    {

        // Clear past uploads
        $this->clearUpload($upload);

        // Use correct import method for type
        if($upload->file_type == 'text/csv') $this->processCsv($upload);
        elseif($upload->file_type == 'sql') $this->processSQL($upload);
        elseif(str_contains($upload->file_type, 'spreadsheetml')) $this->processExcel($upload);

    }

    public function processCSV($upload, $path = null)
    {
        if($path != null) $file = $this->localFile($path);


        else $file = $this->localFile($upload->source);
        try
        {
            Excel::load($file, function($reader) use($upload)
            {
                $data = $reader->toArray();

                if(count($data) > 200 )
                {
                    $this->stageData($data, $upload->id);
                }
                else
                {
                    $this->storeData($data, $upload);
                }
            });
        }
        catch (\Exception $e)
        {
            if(config('app.env') == 'testing')
                App::abort(500, $e);
            else
            {
                return false;
            }
        }

    }

    public function openCSV($file)
    {
        $csvFile = file($file);
        $data = [];
        foreach ($csvFile as $line) {
            $data[] = str_getcsv($line);
        }
        return $data;
    }

    public function processSQL(Upload $upload)
    {
        // load source db
        config(['database.connections.target' => $upload->uploader->settings['db']]);

        switch ($upload->settings['scope'])
        {
            case 'all':
                $data = DB::connection('target')->table($upload->uploader->settings['table'])->pluck($upload->uploader->settings['unique_id']);
                $this->stageData($data, $upload->id);
        }
    }

    public function processExcel(Upload $upload)
    {
        config(['excel.import.force_sheets_collection' => true]);
        $file = $this->localFile($upload->source);

        Excel::load($file, function($reader) use($upload)
        {
            $data = $reader->toArray()[0];

            if(count($data) > 200 )
            {
                $this->stageData($data, $upload->id);
            }
            else
            {
                $this->storeData($data, $upload);
            }

        })->get();

    }

    private function stageData($data, $upload_id)
    {

        // chunk the array to groups of 100
        $chunks = array_chunk($data, 100);
        $files = [];
        $keys = [];

        // generate an array of keys
        foreach($data[0] as $k => $i)
        {
            $keys[] = $k;
        }

        // create a csv for each of the chunks and save to AWS
        foreach ($chunks as $chunk)
        {
            // create file name
            $file_name = random_int(100,99999999) . '_temp.csv';
            $file_path = storage_path() . '/' . $file_name;

            // open csv file
            $csv = fopen($file_path, 'w');

            // add the keys as first row
            fputcsv($csv, $keys);

            // add each row of data to file
            foreach($chunk as $line) fputcsv($csv, $line);

            // close file
            fclose($csv);

            // send to aws
            $files[] = Storage::disk('s3')->putFile('temp_storage', new File($file_path), $file_name);

            // delete temp file
            unlink($file_path);
        }

        $upload = Upload::find($upload_id);
        $upload->parts = $files;
        $upload->save();

        dd('hello');

        dispatch(new ImportChunk($upload_id));

    }

    public function clearUpload(Upload $upload)
    {
        if($upload->uploader->dataset->table_name != null)
        {
            DB::table($upload->uploader->dataset->table_name)->where('upload_id', $upload->id)->delete();
            DB::table('cn_entitables')->where('upload_id', $upload->id)->delete();
        }

        $upload->count = 0;
        $upload->save();
    }

    public function storeData($data, $upload)
    {

        $syncHelper = new Sync();
        $dataProcessor = new DataProcessor();
        $uploader = $upload->uploader;

        $data = $dataProcessor->processData($data , $uploader);

        foreach($data as $key => $value)
        {
            $data[$key]['upload_id'] = $upload->id;
        }
        DB::table($uploader->dataset->table_name)->insert($data);

        $max_id = DB::table($uploader->dataset->table_name)->max('id');

        $syncHelper->postSync($data, (object) $uploader->syncs, $upload->id);

        if($max_id != null) $data = DB::table($uploader->dataset->table_name)->where('id', '>', $max_id)->get();
        else $data = DB::table($uploader->dataset->table_name)->get();
        $upload->count += $data->count();
        $upload->processed_at = Carbon::now();
        $upload->save();

    }

    public function localFile($source)
    {
        $extension = explode('.', $source);
        $extension = end($extension);
        if($extension == 'txt') $extension = 'csv';
        $tempname = storage_path() . "/tmpfile_"  . date('hms') . "." . $extension;
        file_put_contents($tempname, Storage::disk('s3')->get($source));

        return $tempname;
    }

}