<?php

namespace App\Notifications;

use App\Exam;
use App\Upload;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;

class ResultUploaded extends Notification
{
    use Queueable;

    private $Upload;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Upload $upload)
    {
        $this->Upload = $upload;
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
        $U = $this->Upload->User;
        $E = $this->Upload->Exam;

        return (new SlackMessage)
            ->content(
                "Result was uploaded: 
                    Master File: {$E->name}
                    User: {$U->name}
                    IP: {$this->Upload->ip}
                    Location: {$this->Upload->city}, {$this->Upload->country}");
    }
}