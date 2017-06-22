<?php

namespace App\DocumentMgr\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class PrintQueue extends Model
{
    protected $table = 'cn_print_queues';

    protected $fillable = ['cn_document_id', 'created_by', 'queued_at', 'printed_by', 'printed_at', 'settings'];

    protected $casts = ['settings' => 'array'];

    protected $dates = [
        'created_at',
        'printed_at',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function printer()
    {
        return $this->belongsTo(User::class, 'printed_by');
    }

}
