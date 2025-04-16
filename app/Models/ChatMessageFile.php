<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ChatMessageFile extends Pivot
{
    protected $fillable = [
        'message_id',
        'file',
    ];

    public $primaryKey = "message_id";
}
