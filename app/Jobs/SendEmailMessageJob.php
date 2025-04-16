<?php

namespace App\Jobs;

use App\Mail\SendEmailAboutMessage;
use App\Models\Chat;
use App\Models\ChatMessageStatus;
use App\Models\Message;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $message;

    /**
     * SendEmailMessageJob constructor.
     * @param $message Message
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /**
         * @var $chat Chat
         */
        $chat = $this->message->chat;
        $users = $chat->users;
        $filtered_users = $users->filter(function ($user) {
            if ($user->user_id != $this->message->user_id)
                return $user;
        });

        $user_from = User::find($this->message->user_id);
        $user_to = User::find($filtered_users->first()->user_id);

        ChatMessageStatus::create([
            "chat_id" => $chat->id,
            "message_id" => $this->message->id,
            "user_id" => $filtered_users->first()->user_id,
            "company_id" => $filtered_users->first()->company_id,
            'is_read' => false
        ]);


        //if ($user_to->email)
            //Mail::to($user_to->email)->send(new SendEmailAboutMessage($user_from, $user_to, $this->message, $chat->id));


    }
}
