<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class IPAPIResolveFailed extends Notification
{
    use Queueable;

    private $IP;
    private $User;
    private $Error;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($ip, $user, $err)
    {
        $this->IP = $ip;
        $this->User = $user;
        $this->Error = $err;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toSlack($notifiable)
    {
        return [
            'IP' => $this->IP,
            'User' => $this->User->name,
            'Error' => $this->Error->message,
        ];
    }
}
