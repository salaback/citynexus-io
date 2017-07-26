<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 7/19/17
 * Time: 2:38 PM
 */

namespace App\AnalysisMgr;


use App\DataStore\Model\DataSet;
use Carbon\Carbon;

class DatapointProcessor
{

    public function process($scores, $element, $data)
    {
        $queried_data = $data['datasets'][$element['dataset_id']]->where('__created_at', '>', Carbon::now()->subDays($element['trailing']));

        if(isset($element['recent']))
        switch ($element['recent'])
        {
            case 'recent':

                $data = [];

                foreach($queried_data as $item)
                {
                    if(!isset($data[$item->__property_id]))
                        $data[$item->__property_id] = $item;

                    elseif($data[$item->__property_id]->__created_at < $item->__created_at)
                        $data[$item->__property_id] = $item;
                }
                break;

            default:

                $data = $queried_data;
        }
        else
            $data = $queried_data;

        $dataPoint = $element['key'];
        $method = $element['effect']['type'];

        $point = [
            'type' => 'datapoint',
            'dataset_id' => $element['dataset_id'],
            'key' => $dataPoint
        ];
        foreach($data as $item)
        {
            if($method == 'range')
            {
                $true = true;
                if($element['effect']['range']['greaterThan'] != 'false' && $item->$dataPoint < $element['effect']['range']['greaterThan']) $true = false;
                if($element['effect']['range']['lessThan'] != 'false' && $item->$dataPoint > $element['effect']['range']['lessThan']) $true = false;
                if($element['effect']['range']['equalTo'] != 'false' && $item->$dataPoint != $element['effect']['range']['equalTo']) $true = false;

                if($true) $point['effect'] = $element['effect']['range']['add'];
            }
            elseif($element['effect']['effect'] == 'add')
            {
                $point['effect'] = $this->$method($item->$dataPoint);
            }
            elseif($element['effect']['effect'] == 'subtract')
            {
                $point['effect'] = -($this->$method($item->$dataPoint));
            }
            else
            {
                $point['effect'] = $this->$method($item->$dataPoint, $element);
            }

            if(isset($point['effect']))
                $scores[$item->__property_id]['datapoints'][] = $point;
        }

        return $scores;
    }

    private function string($point, $element)
    {
        $pass = false;

        switch ($element['effect']['method'])
        {
            case 'contains':
                if(strpos($point, $element['effect']['test']) !== false)
                    $pass = true;
                break;

            case 'notcontains':
                if(strpos($point, $element['effect']['test']) === false)
                    $pass = true;
                break;

            case 'matches':
                if($point == $element['effect']['test'])
                    $pass = true;
                break;

            case 'notmatches':
                if($point != $element['effect']['test'])
                    $pass = true;
                break;

            case 'blank':
                if($point == null)
                    $pass = true;
                break;

            case 'notblank':
                if($point != null)
                    $pass = true;
                break;
        }

        if($pass)
            return $element['effect']['effect'];
        else
            return 0;
    }

    private function value($value)
    {
        return $value;
    }

    private function log($value)
    {
        return log10($value);
    }

    private function square($value)
    {
        return $value * $value;
    }

    private function cube($value)
    {
        return $value * $value * $value;
    }

    private function root($value)
    {
        return $value ** (1/2);
    }

    private function cuberoot($value)
    {
        return $value ** (1/3);
    }
}