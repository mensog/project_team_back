<?php

namespace App\Listeners;

use App\Events\ProjectCompleted;
use App\Notifications\ProjectCompletedNotification;
use Illuminate\Support\Facades\Notification;

class SendProjectCompletedNotification
{
    public function handle(ProjectCompleted $event)
    {
        $participants = $event->project->participants;
        Notification::send($participants, new ProjectCompletedNotification($event->project));
    }
}
