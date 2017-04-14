<?php

namespace CityNexus\AnalysisMgr;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $fillable = [
        'elements',
        'name',
        'period',
        'timeseries',
        'owned_by',
    ];

    protected $casts = [
        'elements' => 'array',
        'timeseries' => 'boolean'
    ];

    protected $table = 'cn_scores';

    public function owner()
    {
        return $this->belongsTo('\App\User', 'owned_by');
    }

    public function results()
    {
        return $this->hasMany('\CityNexus\AnalysisMgr\ScoreResult');
    }

}

