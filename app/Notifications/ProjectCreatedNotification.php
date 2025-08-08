<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProjectCreatedNotification extends Notification
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
            'user' => [
                'id' => $this->project->user_id,
                'name' => $this->project->user ? "{$this->project->user->first_name} {$this->project->user->last_name}" : 'Неизвестный пользователь'
            ],
            'description' => $this->project->description,
            'project_url' => url("/api/projects/{$this->project->id}"),
            'message' => "Новый проект '{$this->project->name}' от пользователя {$this->project->user->first_name} {$this->project->user->last_name} ожидает вашего подтверждения.",
            'actions' => [
                'approve' => url("/api/projects/{$this->project->id}/approve"),
                'reject' => url("/api/projects/{$this->project->id}/reject"),
            ],
        ];
    }
}
