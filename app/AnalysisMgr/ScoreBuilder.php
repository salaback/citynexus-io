<?php


namespace App\AnalysisMgr;


use App\AnalysisMgr\Model\Score;
use App\AnalysisMgr\Model\ScoreResult;
use Carbon\Carbon;
use App\DataStore\Model\DataSet;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Symfony\Component\VarDumper\Cloner\Data;

class ScoreBuilder
{
    /**
     * @param $value
     * @param $score
     */

    public function runScore($id)
    {

        $score = Score::find($id);
        
        // Create non-time series results
        $this->createNonTimeseries($score);
        // Create time series results

        return true;
    }

    private function createNonTimeseries($score)
    {

        // TODO: Add Scope Elements
        // process elements
        $results = [];
        foreach ($score->elements as $element)
        {
            $results = $this->processElement($element, $results);
        }

        $score_results = [
            'score_id' => $score->id,
            'results' => $results,
            'created_by' => Auth::Id(),
        ];

        ScoreResult::create($score_results);

        return true;
    }

    private function processElement($element, $results, $start = false, $end = false)
    {

        switch ($element['type'])
        {
            case 'logic':
                $new_results = $this->processLogic($element, $start, $end);
                break;
            case 'function':
                $new_results = $this->processFunction($element, $start, $end);
        }

        foreach ($new_results as $key => $i)
        {
            if(!isset($results[$key])) $results[$key] = $i;
            else $results[$key] += $i;
        }

        return $results;
    }

    private function processFunction($element, $start = false, $end = false)
    {

    }

    private function processLogic($element, $start = false, $end = false)
    {
        $dataset = DataSet::find($element['dataset']);
        if($element['logic']['type'] == 'number')
        {
            $properties = DB::table($dataset->table_name)
                ->where($element['key'], $element['logic']['comparison'], $element['logic']['test'])
                ->orderBy('created_at', 'DESC')
                ->get(['property_id', 'location']);
        }
        elseif($element['logic']['type'] == 'point')
        {
            throw \GuzzleHttp\Promise\exception_for('"Not a supoorted logic comparison type";');
            // TODO: Create datapoint based logic type
        }
        else
        {
            throw \GuzzleHttp\Promise\exception_for('"Not a recognized logic comparison type";');
        }

        if($element['effect']['type'] == 'addressed')
        {
            $results = $this->applyScoreToAddress($element, $properties);
        }
        elseif($element['effect']['type'] == 'range')
        {

            throw \GuzzleHttp\Promise\exception_for('"Not a supported effect type";');

            // TODO: Create range based search
        }

        return $results;
    }

    public function applyScoreToAddress($element, $properties)
    {
        $results = [];
        foreach($properties as $property)
        {
            if(!isset($results[$property->property_id])) $results[$property->property_id] = $element['logic']['impact'];
            elseif($element['scope'] == 'all-in') $results[$property->property_id] += $element['logic']['impact'];
        }

        return $results;
    }

    public function calcElement($value, $score)
    {
        $return = null;

        switch ($score->function)
        {
            case 'func':
                $return = $this->runFunc($value, $score);
                break;
            case 'float':
                $return = $this->runFunc($value, $score);
                break;
            case 'range':
                $return = $this->runRange($value, $score);
                break;
            default:
                $return = $this->runText($value, $score);
        }

        return $return;
    }

    private function runFunc($value, $score)
    {
        if($score->func == '+') $return = $value + $score->factor;
        elseif($score->func == '-') $return = $value - $score->factor;
        elseif($score->func == '*') $return = $value * $score->factor;
        elseif($score->func == '/') $return = $value / $score->factor;
        else $return = null;

        return $return;

    }

    private function runRange($value, $score)
    {
        if($score->range == '>' && $value > $score->test) return $score->result;
        elseif($score->range == '<' && $value < $score->test) return $score->result;
        elseif($score->range == '=' && $value == $score->test) return $score->result;

        else return null;

    }

    private function runText($value, $score)
    {
        if($score->function == 'notempty' && $value != null) { return $score->result; }
        elseif($score->function == 'empty' && $value == null) { return $score->result; }
        elseif($score->function == 'equals' && $value == $score->test) { return $score->result; }
        elseif($score->function == 'doesntequal' && $value != $score->test) { return $score->result; }
        elseif($score->function == 'contains'&& stripos($value, $score->test) != false) { return $score->result; }
        elseif($score->function == 'doesntcontain'&& stripos($value, $score->test) === false) { return $score->result; }
        else return null;
    }

}
