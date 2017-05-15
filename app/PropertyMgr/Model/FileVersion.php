<?php

namespace App\PropertyMgr\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FileVersion extends Model
{
    protected $table = 'cn_file_versions';
    protected $fillable = ['file_id', 'type', 'size', 'added_by', 'source'];

}
