<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 3/17/17
 * Time: 4:01 PM
 */

namespace App\Services;


use App\Client;
use App\Jobs\UpgradeProperies;
use CityNexus\CityNexus\Property;
use CityNexus\DataStore\DataSet;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Upgrade
{

    use DispatchesJobs;
    private $client;

    public function client($client)
    {
        $this->client = $client;

        config(['database.connections.tenant.schema' => $this->client->schema]);

        if($this->client->version_id === null)
        {
            $version = 'version_1';
        }
        else
        {
            $version = 'version_' . ($this->client->version_id + 1);
        }

        $this->$version();
    }

    private function version_1()
    {
        // Updates

        // Migrate properties table
        $properties = Property::pluck('id')->chunk(100);

        foreach($properties as $chunk)
        {
            $this->dispatch(new UpgradeProperies($this->client->id, $chunk));
        }

        $this->migrateFileVersions($this->client->settings['user_ids']);

        // Tags
        $this->migrateTags();

        // Tasks
        $this->migrateTasks();

        // Data Sets
        $this->datasets();

        // Save client version
        $this->client->version_id = 1;
        $this->client->save();
    }

    /*
     *
     *
     *   Version 1 supporting functions
     *
     *
     */


    public function migrateFiles($new_id, $old_id)
    {
        $files = DB::table('citynexus_files')->where('property_id', $old_id)->get()->toArray();
        $files = collect($files)->map(function($x){ return (array) $x; })->toArray();

        foreach ($files as $key => $file)
        {
            $files[$key]['property_id'] = $new_id;
        }

        DB::table('cn_files')->insert($files);

    }

    public function migrateFileVersions($user_ids)
    {
        $file_versions = DB::table('citynexus_file_versions')->get();
        $file_versions = collect($file_versions->map(function($x){ return (array) $x; })->toArray());
        $data = [];
        foreach ($file_versions as $key => $file_version) {
            $data[$key] = $file_version;
            $data[$key]['added_by'] = $user_ids[$file_version['added_by']];

        }
        DB::table('cn_file_versions')->insert($data);
    }

    public function migrateComments($new_id, $old_id, $user_ids)
    {
        $comments = DB::table('citynexus_notes')->where('property_id', $old_id)->get()->toArray();

        $comments = collect($comments)->map(function($x){ return (array) $x; })->toArray();

        foreach ($comments as $key => $comment)
        {
            $comments[$key]['cn_commentable_id'] = $new_id;
            $comments[$key]['cn_commentable_type'] = "CityNexus\PropertyMgr\Property";
            $comments[$key]['posted_by'] = $user_ids[$comment['user_id']];
            if(isset($comment['reply_to']))
            {
                $comments[$key]['reply_to'] = $user_ids[$comment['reply_to']] ?: null;
            }
            $comments[$key]['comment'] = $comment['note'];
            unset($comments[$key]['note']);
            unset($comments[$key]['property_id']);
            unset($comments[$key]['user_id']);
        }

        DB::table('cn_comments')->insert($comments);
    }

    public function migrateTags()
    {
        $tags = DB::table('citynexus_tags')->get();

        $tags = collect($tags)->map(function($x){ return (array) $x; })->toArray();

        DB::table('cn_tags')->insert($tags);

        $tag_pivot = DB::table('property_tag')->get();

        $tag_pivot = collect($tag_pivot)->map(function($x){ return (array) $x; })->toArray();

        $pivots = [];
        foreach ($tag_pivot as $k => $i)
        {
            $pivots[$k] = $i;
            $pivots[$k]['tagable_type'] = 'CityNexus\PropertyMgr\Property';
            $pivots[$k]['tagable_id'] = $i['property_id'];
            unset($pivots[$k]['property_id']);
            unset($pivots[$k]['updated_at']);
        }

        DB::table('cn_tagables')->insert($pivots);
    }

    public function migrateTasks()
    {
        $tasks = DB::table('citynexus_tasks')->get();
        $tasks = collect($tasks)->map(function($x){ return (array) $x; })->toArray();

        DB::table('cn_tasks')->insert($tasks);

        $taskables = DB::table('citynexus_taskables')->get();
        $taskables = collect($taskables)->map(function($x){ return (array) $x; })->toArray();

        foreach ($taskables as $k => $i)
        {
            $taskables[$k]['cn_taskable_id'] = $i['citynexus_taskable_id'];
            $taskables[$k]['cn_taskable_type'] = $i['citynexus_taskable_type'];
            unset($taskables[$k]['citynexus_taskable_id']);
            unset($taskables[$k]['citynexus_taskable_type']);
        }

        DB::table('cn_taskables')->insert($taskables);
    }

    public function datasets()
    {
        $data = DB::table('tabler_tables')->whereNull('deleted_at')->get();

        $datasets = [];
        $uploaders = [];

        foreach($data as $k => $i)
        {
            $table_name = str_replace('tabler_', 'cnd_', $i->table_name
            );

            // Create new Data Set
            $dataset = DataSet::create([
                'name' => $i->table_title,
                'table_name' => $table_name,
                'description' => $i->table_description,
                'schema' => json_decode($i->scheme, true),
                'type' => 'profile'
            ]);


            // Create map and sync arrays
            $sync = [];
            $syncs = [];
            $map = [];
            foreach (json_decode($i->scheme, true) as $key => $item)
            {
                $map[$key] = $key;

                if(isset($item['sync']) && $item['sync'] != null)
                {
                    $sync[$item['sync']] = $key;
                }
            }
            $sync['class'] = 'address';

            $syncs[] = $sync;

            // Create uploader
            $dataset->uploaders()->create([
                'name' => "SQL Migration",
                'type' => "sql",
                'settings' => [
                    'table_name' => $i->table_name,
                    'driver'   => 'pgsql',
                    'host'     => config('database.connections.tenant.host'),
                    'database' => config('database.connections.tenant.database'),
                    'username' => config('database.connections.tenant.username'),
                    'password' => config('database.connections.tenant.password'),
                    'charset'  => config('database.connections.tenant.charset'),
                    'prefix'   => config('database.connections.tenant.prefix'),
                    'schema'   => config('database.connections.tenant.schema'),
                    ],
                'map' => $map,
                'syncs' => $syncs
            ]);

        }

    }

}