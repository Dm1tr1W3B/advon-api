<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CommentAdvertisementEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $advertisement;
    private $user;
    private $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($advertisement, $user, $email)
    {
        $this->advertisement = $advertisement;
        $this->user = $user;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.commentAdvertisement')
            ->subject('Новый комментарий к объявлению')
            ->with([
                'advertisement' => $this->advertisement,
                'user' => $this->user,
                'email' => $this->email,
            ]);
    }
}
