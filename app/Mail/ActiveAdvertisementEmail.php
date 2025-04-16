<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActiveAdvertisementEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $advertisement;

    private $email;

    private $author;

    private $image;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($advertisement, $email, $author, $image)
    {
        $this->advertisement = $advertisement;
        $this->email = $email;
        $this->author = $author;
        $this->image = $image;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.activeAdvertisement')
            ->subject('Новое объявление')
            ->with([
                'advertisement' => $this->advertisement,
                'email' => $this->email,
                'author' => $this->author,
                'image' => $this->image,
            ]);
    }
}
