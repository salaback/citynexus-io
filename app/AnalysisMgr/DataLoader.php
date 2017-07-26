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

        $preload[] = $element['tag_id'];

        return $preload;
    }

    public function preLoadDatapointData($preload, $element)
    {
        $preload[$element['dataset_id']][] = $element['key'];

        return $preload;
    }

    public function loadTagData($tags)
    {
        return (array) DB::table('cn_tagables')
            ->whereIn('tag_id', $tags)
            ->where('tagables_type', 'App\PropertyMgr\Model\Property')
            ->get(['tag_id', 'tagables_id', 'created_at', 'deleted_at'])->toArray();
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