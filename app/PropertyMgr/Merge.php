<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 7/11/17
 * Time: 7:07 AM
 */

namespace App\PropertyMgr;


use App\DataStore\Model\DataSet;
use Illuminate\Support\Facades\DB;

class Merge
{
    /**
     * @param $primary
     * @param $secondary array
     */
    public function properties($primary, $secondary)
    {

        DB::table('cn_comments')
            ->where('cn_commentable_type', 'App\PropertyMgr\Model\Property')
            ->whereIn('cn_commentable_id', $secondary)
            ->update(['cn_commentable_id' => $primary]);

        DB::table('cn_addresses')
            ->whereIn('property_id', $secondary)
            ->update(['property_id' => $primary]);

        DB::table('cn_files')
            ->where('cn_fileable_type', 'App\PropertyMgr\Model\Property')
            ->whereIn('cn_fileable_id', $secondary)
            ->update(['cn_fileable_id' => $primary]);

        DB::table('cn_tagables')
            ->where('tagables_type', 'App\PropertyMgr\Model\Property')
            ->whereIn('tagables_id', $secondary)
            ->update(['tagables_id' => $primary]);

        DB::table('cn_task_lists')
            ->where('taskable_type', 'App\PropertyMgr\Model\Property')
            ->whereIn('taskable_id', $secondary)
            ->update(['taskable_id' => $primary]);



        $datasets = DataSet::all();

        foreach($datasets as $dataset)
        {
            DB::table($dataset->table_name)
                ->whereIn('property_id', $secondary)
                ->update(['property_id' => $primary]);
        }

        DB::table('cn_properties')
            ->whereIn('id', $secondary)
            ->delete();
    }
}