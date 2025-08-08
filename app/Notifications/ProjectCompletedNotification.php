<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProjectCompletedNotification extends Notification
{
    use Queueable;

    protected $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'project_id' => $this->project->id,
            'project_name' => $this->project->name,
            'project_url' => url("/api/projects/{$this->project->id}"),
            'message' => "Проект '{$this->project->name}' завершён. Пожалуйста, загрузите сертификат.",
            'action' => url("/api/projects/{$this->project->id}/upload-certificate"),
        ];
    }
}
