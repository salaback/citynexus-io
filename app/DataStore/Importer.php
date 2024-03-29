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
use App\DataStore\Model\Connection;
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
        if($upload->source != null)
        {
            if($upload->file_type == 'text/csv') $this->processCSV($upload);
            elseif($upload->file_type == 'sql') $this->processSQL($upload);
            elseif(str_contains($upload->file_type, 'spreadsheetml')) $this->processExcel($upload);
        }
        else
        {
            if($upload->uploader->type == 'dropbox') $this->processDropbox($upload);
        }

    }

    public function processDropbox(Upload $upload)
    {
        // get token
        $token = Connection::find($upload->uploader->settings['connection_id'])->settings['access_token'];
        // build headers

        foreach($upload->settings['files'] as $path)
        {
            $headers = [
                "Authorization: Bearer " . $token,
                "Dropbox-API-Arg: " . json_encode(['path' => $path])
            ];

            $meta = $this->getDropboxMeta($path, $token);

            $output = $this->runCurl('https://content.dropboxapi.com/2/files/download', false, $headers);

            $fileName = config('schema') . '/dropbox-files/' . Carbon::now()->timestamp . '/' . $meta->name;

            $file = Storage::disk('s3')->put($fileName, $output);

            $uploads[] = [
                'uploader_id' => $upload->uploader_id,
                'source' => $fileName,
                'note' => $upload->note . ' [' . $meta->name . ']',
                'file_type' => $this->typeFromName($meta->name),
                'user_id' => $upload->user_id,
                'size' => $meta->size
            ];
        }
        foreach($uploads as $item)
            Upload::create($item);

        $upload->delete();
    }

    public function typeFromName($name)
    {
        $parts = explode('.', $name);

        switch (end($parts))
        {
            case 'csv':
                return 'text/csv';

            case 'xlsx':
                return 'spreadsheetml';

            case 'xls':
                return 'spreadsheetml';

            default:
                return 'text/csv';
        }
    }



    public function processCSV(Upload $upload, $path = null)
    {
        if($path != null) $file = $this->localFile($path);

        else $file = $this->localFile($upload->source);

        Excel::load($file, function($reader) use($upload)
        {
            $data = $reader->toArray();
            $this->stageData($data, $upload->id);
        });

    }

    public function processExcel(Upload $upload, $path = null)
    {
        config(['excel.import.force_sheets_collection' => true]);

        if($path != null) $file = $this->localFile($path);

        else $file = $this->localFile($upload->source);

        Excel::load($file, function($reader) use($upload)
        {
            $data = $reader->toArray()[0];
            $this->stageData($data, $upload->id);

        })->get();

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
                $data = DB::connection('target')->table($upload->uploader->settings['table'])->get()->toArray();
                break;

            default:
                $data = [];

        }

        $data = json_decode(json_encode($data), true);
        $this->stageData($data, $upload->id);
    }


    private function stageData($data, $upload_id)
    {

        // chunk the array to groups of 100
        $chunks = array_chunk($data, 100);

        $files = [];

        // create a csv for each of the chunks and save to AWS
        foreach ($chunks as $chunk)
        {
            $files[] = $this->storeChunk($chunk);
        }

        $upload = Upload::find($upload_id);
        $upload->parts = $files;
        $upload->save();

        dispatch(new ImportChunk($upload_id));

    }

    public function storeChunk($chunk)
    {
        // create file name
        $file_name = Carbon::now()->toDateTimeString(). '_' . random_int(1000, 99999) . '_temp.json';
        $file_path = storage_path() . '/' . $file_name;

        // open csv file
        $json = fopen($file_path, 'w');

        fwrite($json, json_encode($chunk));

        // close file
        fclose($json);

        // send to aws
        $name = Storage::disk('s3')->putFile('temp_storage', new File($file_path), $file_name);

        // delete temp file
        unlink($file_path);

        return $name;
    }

    public function clearUpload(Upload $upload)
    {
        if($upload->uploader->dataset->table_name != null)
        {
            DB::table($upload->uploader->dataset->table_name)->where('__upload_id', $upload->id)->delete();
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
            $data[$key]['__upload_id'] = $upload->id;
        }
        $max_id = DB::table($uploader->dataset->table_name)->max('__id');

        DB::table($uploader->dataset->table_name)->insert($data);
        $syncHelper->postSync($data, (object) $uploader->syncs, $upload->id);

        if($max_id != null) $data = DB::table($uploader->dataset->table_name)->where('__id', '>', $max_id)->get();
        else $data = DB::table($uploader->dataset->table_name)->get();

        $upload->count += count($data);
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

    private function getDropboxMeta($path, $token)
    {

        // build headers
        $headers = [
            "Authorization: Bearer " . $token,
            "Content-Type: application/json"
        ];

        $data =  json_encode([
            'path' => $path
        ]);

        return json_decode($this->runCurl('https://api.dropboxapi.com/2/files/get_metadata', $data, $headers));
    }

    private function runCurl($url, $data = false, $headers = false)
    {
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, $url);

        if($headers)
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if($data)
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch);

        return $output;
    }

}