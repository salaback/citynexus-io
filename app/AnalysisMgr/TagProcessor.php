<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 6/19/17
 * Time: 4:19 PM
 */

namespace App\AnalysisMgr;


use App\Exceptions\TableBuilder\CreateScoreProcessorException;
use App\PropertyMgr\Model\Property;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TagProcessor
{
    public function process($scores, $element, $data)
    {

        // limit data to the trailing time period
        if($element['trailing'] != 'false')
            $data = $data['tags']->where('created_at', '>', Carbon::now()->subDays($element['trailing']));

        // start building the point array
        $point = [
            'type' => 'tag',
            'tag_id' => $element['tag_id'],
        ];

        if($element['effect']['type'] == 'add') {
            $point['effect'] = $element['effect']['factor'];
        }

        elseif($element['effect']['type'] == 'subtract') {
            $point['effect'] = -$element['effect']['factor'];
        }
        elseif($element['effect']['type'] == 'ignore') {
            $point['effect'] = 'ignore';
        }
        else
        {
            throw new CreateScoreProcessorException('no_option');
        }

        if(isset($element['tags']['tagged_range']) && isset($element['tags']['trashed_range']) && ($element['tags']['tagged_range'] != 'false' or $element['tags']['trashed_range'] != 'false'))
        {
            if($element['tags']['tagged_range'] != 'false')
            {
                $tagged = $data->where('deleted_at', '==', null)->pluck('tagables_id');

                DB::statement('SET SEARCH_PATH = ' . config('schema') . ',public');

                $close = [];
                $tagged = Property::find($tagged);

                foreach($tagged as $property)
                {
                    $npid = Property::where(DB::raw('ST_Distance(location, ST_makepoint(' . $property->location->getLng() . ',' . $property->location->getLat() . '))'), '<', $element['tags']['tagged_range'])->pluck('id')->toArray();
                    if(isset($inTaggedRange))
                    {
                        array_merge($inTaggedRange, $npid);
                    }
                    else
                    {
                        $inTaggedRange = $npid;
                    }
                }

                if(isset($inTaggedRange))
                {
                    foreach($inTaggedRange as $item)
                    {
                        $scores[$item]['tags'][] = $point;
                    }
                }
            }

            if($element['tags']['trashed_range'] != 'false')
            {
                $tagged = $data->where('deleted_at', '!=', null)->pluck('tagables_id');

                DB::statement('SET SEARCH_PATH = ' . config('schema') . ',public');

                $close = [];
                $tagged = Property::find($tagged);

                foreach($tagged as $property)
                {
                    $npid = Property::where(DB::raw('ST_Distance(location, ST_makepoint(' . $property->location->getLng() . ',' . $property->location->getLat() . '))'), '<', $element['tags']['trashed_range'])->pluck('id')->toArray();
                    if(isset($inTaggedRange))
                    {
                        array_merge($inTaggedRange, $npid);
                    }
                    else
                    {
                        $inTaggedRange = $npid;
                    }
                }

                if(isset($inTaggedRange))
                {
                    foreach($inTaggedRange as $item)
                    {
                        $scores[$item]['tags'][] = $point;
                    }
                }
            }

        }
        else
        {
            foreach($data as $item)
            {
                if($item->tag_id == $element['tag_id'])
                {
                    // if tagged properties are included
                    if($element['tags']['tagged'] != "false" && $item->deleted_at == null)
                        $scores[$item->tagables_id]['tags'][] = $point;

                    // if trashed properties are included
                    if($element['tags']['trashed'] != "false" && $item->deleted_at != null)
                        $scores[$item->tagables_id]['tags'][] = $point;
                }
            }
        }

        return $scores;
    }
}