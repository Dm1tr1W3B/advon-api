<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PriceAdvertisementEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $advertisement;
    private $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($advertisement, $email)
    {
        $this->advertisement = $advertisement;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.priceAdvertisement')
            ->subject('Изменение цены в объявлении')
            ->with([
                'advertisement' => $this->advertisement,
                'email' => $this->email
            ]);
    }
}
