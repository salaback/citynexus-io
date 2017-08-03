<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 6/29/17
 * Time: 5:22 PM
 */

namespace App\Services;


use App\Client;
use App\PropertyMgr\PropertySync;
use App\User;
use Illuminate\Support\Facades\DB;

class CityNexusUpgrade
{
    protected $client;


    public function run($id)
    {
        $this->client = Client::find($id);
        $this->client->logInAsClient();
        if(!isset($this->client->settings['user_ids'])) $this->migrateUsers();
        if(!isset($this->client->settings['property_ids'])) $this->migrateProperties();
        if(!isset($this->client->settings['comments']))$this->migrateComments();
        if(!isset($this->client->settings['tags']))$this->migrateTags();
        $this->resetIds();
    }

    private function resetIds()
    {
        $tables = DB::table('information_schema.tables')->where('table_schema', $this->client->schema)->get(['table_schema', 'table_name']);

        foreach($tables as $table)
        {
            try{
                DB::statement("SELECT setval(pg_get_serial_sequence('" . $this->client->schema . "." . $table->table_name . "', 'id'), coalesce(max(id),0) + 1, false) FROM " . $this->client->schema . "." . $table->table_name);
            }
            catch (\Exception $e)
            {
                null;
            }
        }

    }

    public function migrateUsers()
    {
        $users = DB::connection('target')->table('users')->get();
        $userIds = [];
        print('users: ');
        foreach($users as $i)
        {
            $user = User::firstOrNew(['email' => $i->email]);
            $user->first_name = trim($i->first_name);
            $user->last_name = trim($i->last_name);
            $user->password = 'default';
            $membership = [
                'id' => $i->id,
                'password' => $i->password,
                'title' => $i->title,
                'department' => $i->department
            ];

            $user->addMembership($this->client->domain, $membership, true);
            $user->save();

            $userIds[$i->id] = $user->id;

            print('.');
        }

        print('\n');
        $settings = $this->client->settings;
        $settings['user_ids'] = $userIds;
        $this->client->settings = $settings;
        $this->client->save();
    }

    public function migrateProperties()
    {
        $sync = new PropertySync();

        $comments = DB::connection('target')->table('citynexus_notes')
           ->pluck('property_id')->toArray();

        $files = (array) DB::connection('target')->table('citynexus_files')
            ->pluck('property_id')->toArray();

        $tags = (array) DB::connection('target')->table('property_tag')
            ->pluck('property_id')->toArray();

        $pids = array_merge($comments, $files);
        $pids = array_merge($pids, $tags);

        $pids = array_unique($pids);

        $properties = DB::connection('target')->table('citynexus_properties')
            ->whereIn('id', $pids)->pluck('full_address', 'id');

        $new_ids = [];
        print('Properties: ');
        foreach ($properties as $key => $full_address)
        {
            $new_ids[$key] = $sync->getPropertyId($full_address . ', LAWRENCE, MA');
            print('.');
        }

        print('\n');

        $settings = $this->client->settings;
        $settings['property_ids'] = $new_ids;
        $this->client->settings = $settings;
        $this->client->save();
    }

    public function migrateComments()
    {

        $comments = DB::connection('target')->table('citynexus_notes')
            ->get();

        $insert = [];

        $userIds = $this->client->settings['user_ids'];
        $propertyIds = $this->client->settings['property_ids'];

        foreach ($comments as $i)
        {
            $new = [
                'id' => $i->id,
                'comment' => $i->note,
                'posted_by' => $userIds[$i->user_id],
                'created_at' => $i->created_at,
                'updated_at' => $i->updated_at,
                'deleted_at' => $i->deleted_at
            ];
            if($i->reply_to == null)
            {
                $new['cn_commentable_type'] = 'App\PropertyMgr\Model\Property';
                $new['cn_commentable_id'] = $propertyIds[$i->property_id];
            }

            else
            {
                $new['cn_commentable_type'] = 'App\PropertyMgr\Model\Comment';
                $new['cn_commentable_id'] = $i->reply_to;
            }

            $insert[] = $new;

        }

        DB::connection('tenant')->table('cn_comments')->insert($insert);

        $settings = $this->client->settings;
        $settings['comments'] = 'done';
        $this->client->settings = $settings;
        $this->client->save();
    }



    public function migrateTags()
    {

        DB::connection('tenant')->table('cn_tags')->truncate();

        $userIds = $this->client->settings['user_ids'];
        $propertyIds = $this->client->settings['property_ids'];

        $tags = DB::connection('target')->table('citynexus_tags')
            ->get()->toArray();

        $new_tags =[];

        foreach($tags as $i)
        {
            $new_tags[] = ['id' => $i->id, 'tag' => $i->tag];
        }

        DB::connection('tenant')->table('cn_tags')->insert($new_tags);

        $tagables = DB::connection('target')->table('property_tag')
            ->get();

        $insert = [];

        foreach($tagables as $i)
        {
            print $i->created_at . ' - ';

            if(isset($userIds[$i->created_by]))
               $uid = $userIds[$i->created_by];
            else
                $uid = 1;

            $insert[] = [
                'tagables_type' => 'App\PropertyMgr\Model\Property',
                'tag_id' => $i->tag_id,
                'tagables_id' => $propertyIds[$i->property_id],
                'created_by' => $uid,
                'created_at' => $i->created_at,
                'deleted_at' => $i->deleted_at
            ];
        }

        DB::connection('tenant')->table('cn_tagables')->insert($insert);

        $settings = $this->client->settings;
        $settings['tags'] = 'done';
        $this->client->settings = $settings;
        $this->client->save();
    }
}