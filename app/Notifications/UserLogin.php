<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;

class UserLogin extends Notification
{
    use Queueable;

    private $IP;
    private $UserName;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($username, $ip)
    {
        $this->IP = $ip;
        $this->UserName = $username;
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

    public function toSlack($notifiable)
    {
        return (new SlackMessage)
                ->content("User '{$this->UserName}' logged from the following IP: {$this->IP}");
    }
}
