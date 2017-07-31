<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 6/19/17
 * Time: 7:14 AM
 */

namespace App\AnalysisMgr;


use App\AnalysisMgr\Model\Score;
use Bosnadev\Database\Schema\Blueprint;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ScoreProcessor
{
    public function processScore($id)
    {
        // Load Score
        $score = Score::find($id);

        // Create Score
        $scores = $this->createScore($score);

        // Save scores to database
        $this->updateScoreTable($score, $scores);
    }

    private function createScore(Score $score)
    {
        $data = $this->loadData($score);
        return $this->processElements($score->elements, $data);
    }

    private function updateScoreTable($score, $scores)
    {

        $ids = array_filter(array_keys($scores));

        $temp_scores = DB::table('cn_score_' . $score->id)
            ->whereIn('property_id', $ids)
            ->get();

        foreach($temp_scores as $i) $current_scores[$i->property_id] = $i;

        foreach($scores as $id => $item)
        {
            if($id != null)
            {
                if(isset($current_scores[$id]))
                {
                    $update = $this->updateScore($current_scores[$id], $item);
                    DB::table('cn_score_' . $score->id)->where('property_id', $id)->update($update);
                }
                else
                {
                    $insert = [
                        'property_id' => $id,
                        'score' => $this->makeScore($item),
                        'elements' => json_encode($item)
                    ];
                    $new[] = $insert;
                }
            }
        }
        if(isset($insert)) DB::table('cn_score_' . $score->id)->insert($new);
    }

    private function updateScore($old, $new)
    {
        $return = [];
        if($old->history != null)
            $history = json_decode($old->history, true);
        else
            $history = [];

        $history[Carbon::now()->toDateTimeString()] = [
            'score' => $old->score,
            'elements' => json_decode($old->elements)
        ];

        $return['score'] = $new_score = $this->makeScore($new);
        $return['history'] = json_encode($history);
        $return['elements'] = json_encode($new);

        return $return;
    }

    private function makeScore($elements)
    {
        $score = 0;

        foreach($elements as $type)
        {
            foreach ($type as $i)
            {
                if($i['effect'] == 'ignore')
                {
                    $score = null;
                    break;
                }
                else
                {
                    $score += $i['effect'];
                }
            }
            if($score === null) break;
        }

        return $score;
    }

    private function processElements($elements, $data)
    {
        $router = new ProcessRouter();
        $scores = [];
        foreach($elements as $element)
        {
            switch ($element['type'])
            {
                case 'tag':
                    $scores = $router->tags($scores, $element, $data);
                    break;
                case 'datapoint':
                    $scores = $router->datapoint($scores, $element, $data);
                    break;
            }
        }

        return $scores;
    }

    private function loadData(Score $score)
    {
        $loader = new DataLoader();

        // extract data needed to load actual data in
        // as few queries as possible.

        $preLoad = $this->preLoadData($score);

        // load data
        $data = [];

        foreach($preLoad as $key => $item)
        {
            switch ($key)
            {
                case 'tags':
                    $data['tags'] = $loader->loadTagData($item);
                    break;

                case 'datasets':
                    $data['datasets'] = $loader->loadDatasets($item);
            }
        }

        return $data;
    }

    public function preLoadData(Score $score)
    {
        $loader = new DataLoader();

        $preload = [
            'tags' => [],
            'datasets' => []
        ];

        // prepare
        foreach($score->elements as $element)
        {
            switch ($element['type'])
            {
                case 'tag':
                    $preload['tags'] = $loader->preLoadTagData($preload['tags'], $element);
                    break;
                case 'datapoint':
                    $preload['datasets'] = $loader->preLoadDatapointData($preload['datasets'], $element);
                    break;
            }
        }

        return $preload;
    }




}