<?php

namespace App\FormMgr\Model;

use App\DataStore\Model\DataSet;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $table = 'cn_forms';

    protected $fillable = ['name', 'description', 'owner_id', 'form', 'port', 'settings', 'permissions'];

    protected $casts = [
        'form' => 'array',
        'port' => 'array',
        'settings' => 'array',
        'permissions' => 'array'
    ];

    public function dataset()
    {
        return $this->belongsTo(DataSet::class, 'dataset_id');
    }
}
