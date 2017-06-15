<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 6/14/17
 * Time: 12:52 PM
 */

namespace App\AnalysisMgr;


class Statistics
{
    public function median($array)
    {
        return $array[round(count($array) / 2)];

    }

    public function mean($array)
    {
        return array_sum($array)/count($array);
    }

    public function standard_deviation(array $a, $sample = false) {
        $n = count($a);
        if ($n === 0) {
            trigger_error("The array has zero elements", E_USER_WARNING);
            return false;
        }
        if ($sample && $n === 1) {
            trigger_error("The array has only 1 element", E_USER_WARNING);
            return false;
        }
        $mean = array_sum($a) / $n;
        $carry = 0.0;
        foreach ($a as $val) {
            $d = ((double) $val) - $mean;
            $carry += $d * $d;
        };
        if ($sample) {
            --$n;
        }
        return sqrt($carry / $n);
    }
}
