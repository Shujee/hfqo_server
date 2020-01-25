<?php

namespace App\Notifications;

use App\Exam;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;

class ExamUploaded extends Notification
{
    use Queueable;

    private $Exam;
    private $is_new;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Exam $exam, bool $new)
    {
        $this->Exam = $exam;
        $this->is_new = $new;
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
        $Action = ($this->is_new? 'uploaded' : 'updated');

        return (new SlackMessage)
            ->content(
                "Master file $Action
                    Name: {$this->Exam->name}
                    User: {$this->Exam->Uploader->name}
                    QAs: {$this->Exam->qa_count}");
    }
}