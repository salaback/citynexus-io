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

    public function loadTagData($tags)
    {
        return (array) DB::table('cn_tagables')
            ->whereIn('tag_id', $tags)
            ->where('tagables_type', 'App\PropertyMgr\Model\Property')
            ->get(['tag_id', 'tagables_id', 'created_at', 'deleted_at'])->toArray();
    }
}