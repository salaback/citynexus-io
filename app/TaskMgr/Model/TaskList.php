<?php

namespace App\TaskMgr\Model;

use App\PropertyMgr\Model\Entity;
use App\PropertyMgr\Model\Property;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskList extends Model
{
    use SoftDeletes;
    protected $table = 'cn_task_lists';
    protected $fillable = ['name', 'taskable_type', 'taskable_id'];

    public function tasks()
    {
        return $this->hasMany(Task::class)->orderBy('due_at', 'ASC');
    }

    public function completedTasks()
    {
        return $this->hasMany(Task::class)->orderBy('deleted_at', 'ASC')->onlyTrashed();
    }

    public function taskable()
    {
        return $this->morphTo();
    }

}
