<?php

namespace CityNexus\AnalysisMgr;

use Illuminate\Database\Eloquent\Model;

class ScoreResult extends Model
{
    protected $fillable = [
        'score_id',
        'results',
        'period_start',
        'period_end',
        'created_by',
    ];

    protected $casts = [
        'results' => 'array',
    ];

    protected $table = 'cn_score_results';

    public function creator()
    {
        return $this->belongsTo('\App\User', 'created_by');
    }

    public function score()
    {
        return $this->belongsTo('\CityNexus\AnalysisMgr\ScoreResults.php');
    }

}

