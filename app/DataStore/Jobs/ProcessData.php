<?php

namespace App\DataStore\Jobs;

use App\Client;
use App\DataStore\TableBuilder;
use App\Organization;
use Carbon\Carbon;
use App\DataStore\Model\Uploader;
use App\DataStore\Store;
use App\DataStore\Model\Upload;
use App\PropertyMgr\Sync;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class ProcessData implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    private $data;
    private $client_id;
    private $upload_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, $upload_id)
    {
        $this->data = $data;
        $this->upload_id = $upload_id;
        $this->client_id = config('client.id');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // log in client
        Client::find($this->client_id)->logInAsClient();

        // load helpers
        $store = new Store();
        $syncHelper = new Sync();
        $tableBuilder = new TableBuilder();

        $upload = Upload::find($this->upload_id);
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

        $data = $store->processData($this->data , $uploader);
        $table_name = $tableBuilder->syncTable($uploader->dataset);
        $final_data = [];

        $map = $uploader->map;
        // load data
        foreach($data as $row)
        {
            $new_row['upload_id'] = $upload->id;
            foreach ($row as $key => $item)
            {
                if($map[$key]['key'] != null)
                {
                    if(is_string($item)) $new_row[$key] = trim($item);
                    else $new_row[$key] = $item;
                }
            }
            $final_data[] = $new_row;
        }

        $max_id = DB::table($table_name)->max('id');

        DB::table($table_name)->insert($final_data);

        if($max_id != null) $data = DB::table($table_name)->where('id', '>', $max_id)->get();
        else $data = DB::table($table_name)->get();

        $syncHelper->postSync($data, (object) $uploader->syncs, $upload->id);

        $upload->processed_at = Carbon::now();
        $upload->save();

    }
}
