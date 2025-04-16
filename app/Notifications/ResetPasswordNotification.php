<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    private $url;

    /**
     * @var string
     */
    private $email;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $url, string $email)
    {
        $this->url = $url;
        $this->email = $email;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    //->from('mail@api2.mycars.test.ut.in.ua', 'MyCars-USA')
                    ->subject(__('email.Reset Password Notification'))
                    ->view(
                        'emails.resetPasswordNotification', ['url' => $this->url, 'email' => $this->email]
                    );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
