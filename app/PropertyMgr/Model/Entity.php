<?php

namespace App\PropertyMgr\Model;

use App\DataStore\Model\DataSet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
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
        return $this->morphedByMany('App\PropertyMgr\Model\Property', 'entitables', 'cn_entitables')->withPivot('role', 'upload_id');
    }

    public function getBuildingsAttribute()
    {
        return $this->properties->where('is_building', true);
    }

    public function getUnitsAttribute()
    {
        return $this->properties->where('is_unit', true);
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

    public function getDatasetsAttribute()
    {

        $datasets = [];

        $ids = $this->properties()->pluck('id');

        foreach(DataSet::all() as $dataset)
        {
            $datasets[$dataset->id] = DB::table($dataset->table_name)->where('property_id', $ids)->get();
            if($datasets[$dataset->id]->count() == null) unset($datasets[$dataset->id]);
        }

        return $datasets;
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'cn_commentable');
    }
}
