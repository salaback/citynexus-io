<?php

namespace CityNexus\AnalysisMgr;

use App\User;
use Illuminate\Database\Eloquent\Model;

class CalculatedValue extends Model
{
    protected $table = "cn_calculated_values";

    protected $fillable = ['name', 'type', 'key', 'user_id', 'settings'];

    protected $casts = [
      'settings' => 'array'
    ];


    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
