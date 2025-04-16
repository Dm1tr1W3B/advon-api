<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = "chat_message";
    protected $fillable = [
        "chat_id",
        "user_id",
        "company_id",
        "text",
        "advertisement_id",
    ];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function files()
    {
        return $this->hasMany(ChatMessageFile::class, 'message_id');
    }

    public function messagesUnread()
    {
        return $this->hasMany(ChatMessageStatus::class, 'message_id', 'id');
    }

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class);
    }
}
