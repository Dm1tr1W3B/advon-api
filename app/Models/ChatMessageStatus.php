<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ChatMessageStatus extends Pivot
{
    protected $table = "chat_message_status";

    public function scopeUnread($query,$id)
    {
        return $query->where('is_read', '=', false)->whereNot('user_id',$id);
    }

}
