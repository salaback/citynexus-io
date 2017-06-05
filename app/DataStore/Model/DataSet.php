<?php

namespace App\DataStore\Model;

use App\User;
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
        'owner_id'
    ];

    protected $casts = [
      'schema' => 'array'
    ];

    protected $table = 'cn_datasets';

    public function uploaders()
    {
        return $this->hasMany('\App\DataStore\Model\Uploader', 'dataset_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
