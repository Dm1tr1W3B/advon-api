<?php

namespace App\Http\Resources;

use App\Http\Helpers\ChatHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        /**
         * @var $user User
         */
        $chatHelper = new ChatHelper();
        $user = auth()->user();
        $result = [
            'id' => $this->id,
            'type' => $this->type,
            'favorite' => $this->users->where('chat_id', $this->id)->where('user_id', $user->id)->first()->favorite,
            'blocked' => $this->users->where('chat_id', $this->id)->where('user_id', $user->id)->first()->blocked,
            'count_of_messages' => count($this->messages),
            'count_of_not_read' => count($this->messagesUnread),
            'last_message' => $this->getLastMessage->first(),
            'user' => UserResource::collection(User::find($chatHelper->getAnotherUser($this)->pluck('user_id'))),
        ];

        return $result;
    }
}

