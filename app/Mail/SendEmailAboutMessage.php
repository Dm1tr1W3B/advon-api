<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendEmailAboutMessage extends Mailable
{
    use Queueable, SerializesModels;

    private $user_from;
    private $email;
    private $chat_id;

    /**
     * Create a new message instance.
     */
    public function __construct($user_from, $email, $chat_id)
    {
        $this->user_from = $user_from;
        $this->email = $email;
        $this->chat_id = $chat_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.message')
            ->subject('Новое сообщение в чате')
            ->with([
                "user_from" => $this->user_from,
                'email' => $this->email,
                'chat_id' => $this->chat_id,
            ]);
    }
}
