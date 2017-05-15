<?php

namespace App\PropertyMgr\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Phaza\LaravelPostgis\Eloquent\PostgisTrait;

class RawEntity extends Model
{

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'title',
        'suffix',
        'company_name',
        'company_structure',
        'full_name',
        'entity_id'
        ];


    protected $table = 'cn_raw_entities';

    public $timestamps = false;

    public function entity()
    {
        return $this->belongsTo('App\PropertyMgr\Model\Entity');
    }

}
