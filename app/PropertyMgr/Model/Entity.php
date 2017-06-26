<?php

namespace App\PropertyMgr\Model;

use App\DataStore\Model\DataSet;
use App\Tag;
use App\TaskMgr\Model\TaskList;
use App\Traits\SaveToUpper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Phaza\LaravelPostgis\Eloquent\PostgisTrait;

class Entity extends Model
{
    use SoftDeletes;
    use SaveToUpper;
    
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
            $query = DB::table($dataset->table_name)->where('property_id', $ids)->get();

            if($query->count() != 0)
            {
                $datasets[$dataset->id] = $query;
            }
        }

        return $datasets;
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'cn_commentable');
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'tagables', 'cn_tagables')->whereNull('cn_tagables.deleted_at')->orderBy('cn_tagables.created_at')->withPivot('created_by', 'created_at', 'id');
    }

    public function trashedTags()
    {
        return $this->morphToMany(Tag::class, 'tagables', 'cn_tagables')->whereNotNull('cn_tagables.deleted_at')->orderBy('cn_tagables.deleted_at')->withPivot('deleted_at', 'deleted_by', 'created_by', 'created_at', 'id');
    }
    public function files()
    {
        return $this->morphMany('App\PropertyMgr\Model\File', 'cn_fileable');
    }

    public function taskLists()
    {
        return $this->morphMany(TaskList::class, 'taskable');
    }

    public function getTasksAttribute()
    {
        $lists = [];

        foreach($this->properties as $property) $lists = array_merge($lists, $property->taskLists->pluck('id')->toArray());
        $lists = array_merge($lists, $this->taskLists->pluck('id')->toArray());

        return TaskList::findMany($lists);
    }

}
