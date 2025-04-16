<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $table = "chats";
    protected $fillable = [
        'type'
    ];

    public function messages()
    {
        return $this->hasMany(Message::class, 'chat_id', 'id');
    }

    public function messagesUnread()
    {
        return $this->hasMany(ChatMessageStatus::class, 'chat_id', 'id')
            ->where('is_read',false)
            ->where('user_id','=',auth()->user()->id);
    }
    public function getLastMessage()
    {
        return $this->hasMany(Message::class, 'chat_id', 'id')->latest();
    }
    public function messagesStatuses()
    {
        return $this->hasMany(ChatMessageStatus::class, 'chat_id', 'id');
    }

    public function users()
    {
        return $this->hasMany(ChatUser::class, 'chat_id', 'id');
    }
}
