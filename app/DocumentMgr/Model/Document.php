<?php

namespace App\DocumentMgr\Model;

use App\DocumentMgr\Model\DocumentTemplate;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'cn_documents';

    protected $fillable = ['cn_document_template_id', 'documented_id', 'documented_type', 'history', 'created_by', 'status', 'sender_id', 'body', 'related'];

    protected $casts = [
        'elements' => 'array',
        'history' => 'array',
        'related' => 'array'
    ];

    public function template()
    {
        return $this->belongsTo(DocumentTemplate::class, 'cn_document_template_id');
    }

    public function sender()
    {
        return $this->belongsTo('sender_id');
    }
}