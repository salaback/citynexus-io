<?php

namespace CityNexus\DataStore;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    protected $fillable = [
        'uploader_id',
        'source',
        'size',
        'file_type',
        'note',
        'queues',
        'new_property_ids',
        'user_id'
    ];

    protected $casts = ['new_property_ids' => 'array'];

    protected $dates = ['processed_at', 'deleted_at', 'created_at', 'updated_at'];

    protected $table = 'cn_uploads';

    public function user()
    {
        return $this->belongsTo('\App\User');
    }

    public function uploader()
    {
        return $this->belongsTo('\CityNexus\DataStore\Uploader');
    }

}

