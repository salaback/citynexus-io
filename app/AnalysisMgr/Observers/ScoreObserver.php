<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 7/7/17
 * Time: 7:39 PM
 */

namespace App\AnalysisMgr\Observers;

use App\AnalysisMgr\Jobs\ProcessScore;
use App\AnalysisMgr\Model\Score;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Schema;

class ScoreObserver
{
    use DispatchesJobs;

    public function __construct()
    {

    }

    public function created(Score $score)
    {
        Schema::create('cn_score_' . $score->id, function (Blueprint $table) {
            $table->integer('property_id')->unique()->unsigned();
            $table->float('score')->nullable();
            $table->json('elements')->nullable();
            $table->json('history')->nullable();
        });

        $this->dispatch(new ProcessScore($score->id));
    }

    public function updated(Score $score)
    {
        $this->dispatch(new ProcessScore($score->id));
    }

}