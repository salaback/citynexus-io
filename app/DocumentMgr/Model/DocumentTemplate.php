<?php

namespace App\DocumentMgr\Model;

use App\DocumentMgr\Model\Document;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DocumentTemplate extends Model
{
    protected $table = 'cn_document_templates';

    protected $fillable = ['type', 'name', 'visible_on', 'body'];

    protected $casts = [
        'visible_on' => 'array'
    ];

    public function documents()
    {
        return $this->belongs(Document::class);
    }
}
