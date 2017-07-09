<?php


namespace App\PropertyMgr;

use App\Jobs\Geocode;
use App\PropertyMgr\Model\Entity;
use App\PropertyMgr\Model\RawEntity;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class Sync
{

//    public function address($data, $syncs)
//    {
//        $prop = new PropertySync();
//        dd($data);
//        return $prop->address($data, $syncs);
//    }

    public function postSync($data, $syncs, $upload_id)
    {
        foreach($syncs as $sync)
        {
            switch ($sync['class'])
            {
                case 'entity':
                    $this->entitySync($data, $sync, $upload_id);
                    break;
            }
        }

    }

    private function entitySync($data, $sync, $upload_id)
    {

        $entitySync = new EntitySync();
        foreach($data as $i)
        {
            // parse based on name type
            if($sync['type'] == 'unparsed')
            {
                // extract full name key
                $key = $sync['full_name'];

                // check for raw entry, if none create one.
                $raw = RawEntity::firstOrCreate(['full_name' => trim(strtoupper($i->$key))]);

                // if raw entity doesn't have an entity ID, parse name.
                if($raw->entity_id == null && $i->$key != null)
                {
                    if(isset($sync['format']))
                        $format = $sync['format'];
                    else
                        $format = null;

                    $parsed = $entitySync->parseName($i->$key, $format);
                    $entity = Entity::firstOrCreate($parsed);
                    $raw->entity_id = $entity->id;
                    $raw->save();
                }
                elseif($raw->entity_id != null)
                {
                    $entity = $raw->entity;
                }
                else{
                    break;
                }

            }elseif($sync['type'] == 'parsed')
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
                    if($i->$key != null) $parsed = $entitySync->parseName($i->$key);

                    $entity = Entity::firstOrCreate($parsed);
                    $raw->entity_id = $entity->id;
                    $raw->save();
                }
                else
                {
                    $entity = $raw->entity;
                }

            }

            $entity->properties()->attach($i->property_id, ['upload_id' => $upload_id, 'role' => $sync['role']]);

        }
    }

}