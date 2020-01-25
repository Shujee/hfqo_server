<?php

namespace App\Http\Controllers;

use Illuminate\Notifications\Notifiable;

class SlackAgent
{
    use Notifiable;
    
    /**
     * Route notifications for the Slack channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForSlack($notification)
    {
        return 'https://hooks.slack.com/services/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';
    }
}