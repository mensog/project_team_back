<?php

namespace App\Listeners;

use App\Events\ProjectCreated;
use App\Models\User;
use App\Notifications\ProjectCreatedNotification;
use Illuminate\Support\Facades\Notification;

class SendProjectCreatedNotification
{
    public function handle(ProjectCreated $event)
    {
        if (!$event->project->user->is_admin) {
            $admins = User::where('is_admin', true)->get();
            Notification::send($admins, new ProjectCreatedNotification($event->project));
        }
    }
}
