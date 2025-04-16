<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ChatUser extends Model
{
    protected $table = 'chat_user';

    protected $fillable=[
        "chat_id",
        "user_id",
        "company_id",
        "blocked",
        "favorite"
    ];

  public function ChatMessageStatus()
    {
        return $this->hasMany(ChatMessageStatus::class, 'chat_id', 'chat_id')
            ->where('is_read', false)
            ->where('user_id', '=', auth()->user()->id);
    }



    public function scopeFavorite($query)
    {
        return $query->where('favorite', true);
    }

    public function scopeBlocked($query)
    {
        return $query->where('blocked', true);
    }

    public function scopeActive($query)
    {
        return $query->where('blocked', false);
    }
}
