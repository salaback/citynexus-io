<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 6/19/17
 * Time: 10:00 AM
 */

namespace App\AnalysisMgr;


use App\PropertyMgr\Model\Tag;
use Illuminate\Support\Facades\DB;

class DataLoader
{
    public function preLoadTagData($preload, $element)
    {
        if(isset($preload[$element['tag_id']]))
        {
            if($element['tags']['tagged_range'] != 'false' && ((int) $element['tags']['tagged_range']) > $preload[$element['tag_id']])
                $preload[$element['tag_id']] = $element['tags']['trashedRange'];

            if($element['tags']['trashed_range'] != 'false' && ((int) $element['tags']['trashed_range']) > $preload[$element['tag_id']])
                $preload[$element['tag_id']] = $element['tags']['trashed_range'];

        }
        else
        {
            if(isset($element['tags']['tagged_range']) && (int) $element['tags']['tagged_range'] > 0)
                $preload[$element['tag_id']] = (int) $element['tags']['tagged_range'];
            elseif(isset($element['tags']['trashed_range']) && (int) $element['tags']['trashed_range'] > 0)
                $preload[$element['tag_id']] = (int) $element['tags']['trashed'];
            else
                $preload[$element['tag_id']] = false;
        }

        return $preload;
    }

    public function preLoadDatapointData($preload, $element)
    {
        $preload[$element['dataset_id']][] = $element['key'];

        return $preload;
    }

    public function loadTagData($tags)
    {
        return DB::table('cn_tagables')
            ->whereIn('tag_id', array_keys($tags))
            ->where('tagables_type', 'App\PropertyMgr\Model\Property')
            ->get(['tag_id', 'tagables_id', 'created_at', 'deleted_at']);
    }

    public function loadDatasets($datasets, $buildings = 'buildings')
    {

        $return = [];
        $table_ids = array_keys($datasets);

        $tables = DB::table('cn_datasets')->whereIn('id', $table_ids)->get();

        foreach($datasets as $table => $keys)
        {
            $table = $tables->where('id', $table)->first();

            switch ($buildings)
            {
                case 'buildings':
                    $select = '__building_id AS __property_id, __created_at, ';
                    break;
                default:
                    $select = '__property_id, __created_at, ';
            }

            for($i = 0; $i < count($keys); $i++)
            {
                if($i != 0) $select .= ', ';
                $select .= $keys[$i];
            }

            $return[$table->id] = DB::table($table->table_name)->select(DB::raw($select))->get();
        }

        return $return;
    }
}