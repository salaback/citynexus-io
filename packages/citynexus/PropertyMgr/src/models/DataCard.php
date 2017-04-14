<?php

namespace CityNexus\PropertyMgr;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataCard extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'user_id',
        'public_folder',
        'private_folder',
        'elements',
    ];

    protected $casts = [
      'elements' => 'array'
    ];

    protected $table = 'cn_units';

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('\App\User');
    }

}
