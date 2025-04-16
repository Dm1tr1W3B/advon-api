<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FeedbackEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $email;
    private $name;
    private $feedback_type;
    private $feedback_message;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $email, string $name, string $feedback_type, string $feedback_message)
    {
        $this->email = $email;
        $this->name = $name;
        $this->feedback_type = $feedback_type;
        $this->feedback_message = $feedback_message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.feedback')
            ->subject('Предложение о сотрудничестве')
            ->with([
                'email' => $this->email,
                'name' => $this->name,
                'feedback_type' => $this->feedback_type,
                'feedback_message' => $this->feedback_message,
            ]);
    }
}
