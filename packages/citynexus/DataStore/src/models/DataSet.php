<?php

namespace CityNexus\DataStore;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataSet extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'table_name',
        'description',
        'schema',
        'access',
        'deny',
        'type',
    ];

    protected $casts = [
      'schema' => 'array'
    ];

    protected $table = 'cn_datasets';

    public function uploaders()
    {
        return $this->hasMany('\CityNexus\DataStore\Uploader', 'dataset_id');
    }

}