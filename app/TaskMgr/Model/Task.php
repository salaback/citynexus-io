<?php

namespace App\TaskMgr\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;
use App\TaskMgr\Model\TaskList;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;
    protected $table = 'cn_tasks';

    protected $fillable = ['name', 'task_list_id', 'body', 'created_by', 'assigned_to', 'due_at', 'deleted_by', 'triggers', 'history', 'status'];

    protected $cast = [
        'triggers' => 'array',
        'history' => 'array',
        'due_at' => 'date'
    ];

    protected $dates = [
        'due_at',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    public function taskList()
    {
        return $this->belongsTo(TaskList::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

}
