<?php

namespace App\Notifications;

use App\Download;
use App\Exam;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;

class ExamDownloaded extends Notification
{
    use Queueable;

    private $DL;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Download $dl)
    {
        $this->DL = $dl;
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
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->content(
                "Master file downloaded:
                    Name: {$this->DL->Access->Exam->name}
                    User: {$this->DL->User->name}
                    IP: {$this->DL->ip}
                    Location: {$this->DL->city}, {$this->DL->country}");
    }
}
