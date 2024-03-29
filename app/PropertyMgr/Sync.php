<?php


namespace App\PropertyMgr;

use App\Jobs\Geocode;
use App\PropertyMgr\Model\Entity;
use App\PropertyMgr\Model\EntityAddress;
use App\PropertyMgr\Model\RawEntity;
use App\PropertyMgr\Model\Tag;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class Sync
{

    public function __construct()
    {
        $this->entitySync = new EntitySync();
    }

//    public function address($data, $syncs)
//    {
//        $prop = new PropertySync();
//        dd($data);
//        return $prop->address($data, $syncs);
//    }

//
//        if($uploader->hasSyncClass('tag')){
//        }

    public function postSync($data, $syncs, $upload_id)
    {
        foreach($syncs as $sync)
        {
            switch ($sync['class'])
            {
                case 'entity':
                    $this->entitySync($data, $sync, $upload_id);
                    break;

                case 'tag':
                     $this->addTags($data, $sync);

            }
        }

    }

    private function processUnparsed($i, $sync)
    {
        $entity = null;
        // extract full name key
        $key = $sync['full_name'];

        if(isset($i[$key]) && $i[$key] != null)
        {

            // check for raw entry, if none create one.
            $raw = RawEntity::firstOrCreate(['full_name' => trim(strtoupper($i[$key]))]);

            // if raw entity doesn't have an entity ID, parse name.
            if($raw->entity_id == null)
            {
                if(isset($sync['format']))
                    $format = $sync['format'];
                else
                    $format = null;

                $parsed = $this->entitySync->parseName($i[$key], $format);
                $entity = Entity::firstOrCreate($parsed);
                $raw->entity_id = $entity->id;
                $raw->save();
            }
            elseif($raw->entity_id != null)
            {
                $entity = $raw->entity;
            }
        }

        return $entity;
    }

    private function processParsed($i, $sync)
    {
        // create parsed array of keys
        foreach($sync['entity'] as $key => $value)
        {
            $parsed[$key] = trim(strtoupper($i[$value]));
        }

        // check if raw entry exists or else create one
        $raw = RawEntity::firstOrCreate([$parsed]);

        // if raw address doesn't have an entity ID, parse name.
        if($raw->entity_id == null)
        {
            if($i->$key != null) $parsed = $this->entitySync->parseName($i->$key);

            $entity = Entity::firstOrCreate($parsed);
            $raw->entity_id = $entity->id;
            $raw->save();
        }
        else
        {
            $entity = $raw->entity;
        }

        return $entity;
    }

    private function entitySync($data, $sync, $upload_id)
    {

        $entitySync = new EntitySync();

        foreach($data as $i)
        {
            $entity = null;

            // parse based on name type
            switch ($sync['type'])
            {
                case 'unparsed':
                    $entity = $this->processUnparsed($i, $sync);
                    break;

                case 'parsed':
                    $entity = $this->processParsed($i, $sync);
                    break;
            }

            if(isset($entity) && $entity != null)
            {

                if(isset($i['__property_id']) && $i['__property_id'] != null)
                $entity->properties()->syncWithoutDetaching([$i['__property_id'] => ['upload_id' => $upload_id, 'role' => $sync['role']]]);

                if(isset($sync['address']))
                {
                    $address = EntityAddress::firstOrCreate($entitySync->syncAddress($i, $sync['address']));
                    $entity->addresses()->attach($address);
                    if(isset($sync['address']['setPrimary']) && $sync['address']['setPrimary'] == true)
                    {
                        $entity->mailingAddress()->attach($address);
                    }
                }
            }
        }
    }

    public function addTags($data, $sync)
    {
        switch ($sync['method'])
        {
            case 'value':
                $this->valueTags($data, $sync);
                break;
            case 'comma':
                $this->valueTags($data, $sync, true);
        }
    }

    private function valueTags($data, $sync, $comma = false)
    {
        $tags = [];
        $insert =[];
        foreach($data as $row)
        {
            if($row['__property_id'] != null && $row[$sync['dataPoint']] != null)
            {
                if($comma)
                {
                    $parts = explode(',', $row[$sync['dataPoint']]);

                    foreach($parts as $part)
                    {
                        $tags[trim($part)] = $row['__property_id'];
                    }
                }
                else
                {
                    $tags[$row[$sync['dataPoint']]][] = $row['__property_id'];
                }
            }
        }

        foreach($tags as $tag => $ids)
        {
            $tag = Tag::firstOrCreate(['tag' => $tag]);

            $tagables = DB::table('cn_tagables')->get();

            $insert = [];

            foreach($ids as $id)
            {

                // build up new tag
                $newtag = [
                    'tag_id' => $tag->id,
                    'tagables_type' => 'App\\PropertyMgr\\Model\\Property',
                    'tagables_id' => $id,
                ];

                // check for undeleted existing tags
                $count = $tagables->where('tag_id', $tag->id)
                    ->where('tagables_type', 'App\\PropertyMgr\\Model\\Property')
                    ->where('tagables_id', $id)
                    ->where('deleted_at', null)
                    ->count();

                // if not currently tagged, add one new tag
                if($count == 0 && !in_array($newtag, $insert))
                    $insert[] = $newtag;
                }
            }

            foreach ($insert as $key => $item) $insert[$key]['created_at'] = Carbon::now();

        DB::table('cn_tagables')->insert($insert);

        return true;
    }


}