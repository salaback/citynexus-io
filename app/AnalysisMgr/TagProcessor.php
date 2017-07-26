<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 6/19/17
 * Time: 4:19 PM
 */

namespace App\AnalysisMgr;


use App\Exceptions\TableBuilder\CreateScoreProcessorException;

class TagProcessor
{
    public function process($scores, $element, $data)
    {
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

        foreach($data['tags'] as $item)
        {
            if($item->tag_id == $element['tag_id'])
            {
                // if tagged properties are included
                if($element['tags']['tagged']&& $item->deleted_at == null)
                    $scores[$item->tagables_id]['tags'][] = $point;

                // if trashed properties are included
                if($element['tags']['trashed'] && $item->deleted_at != null)
                    $scores[$item->tagables_id]['tags'][] = $point;
            }
        }

        return $scores;
    }
}