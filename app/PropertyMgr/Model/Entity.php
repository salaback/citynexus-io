<?php

namespace App\PropertyMgr\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Phaza\LaravelPostgis\Eloquent\PostgisTrait;

class Entity extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'company_name',
        'company_structure',
    ];

    protected $table = 'cn_entities';


    public function properties()
    {
        return $this->morphedByMany('App\PropertyMgr\Model\Property', 'entitables', 'cn_entitables');
    }


    public function getNameAttribute()
    {
        if($this->company_name != null)
        {
            return trim($this->company_name . ' ' . $this->company_structure);
        }
        else
        {
            return trim($this->first_name . ' ' . trim($this->middle_name . ' ' . $this->last_name));
        }
    }
}
