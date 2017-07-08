<?php

namespace CityNexus\CityNexus;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Upload extends Model
{

    use SoftDeletes;

    protected $table = 'citynexus_uploads';
    protected $fillable = ['table_id', 'note'];

    public function table()
    {
        return $this->belongsTo('CityNexus\CityNexus\Table');
    }

}
