<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 6/19/17
 * Time: 4:18 PM
 */

namespace App\AnalysisMgr;


class ProcessRouter
{
    public function tags($scores, $element, $data)
    {
        $tagProcessor = new TagProcessor();
        return $tagProcessor->process($scores, $element, $data);
    }

    public function datapoint($scores, $element, $data)
    {
        $datpointProcessor = new DatapointProcessor();
        return $datpointProcessor->process($scores, $element, $data);
    }
}