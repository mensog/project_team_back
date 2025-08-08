<?php

namespace App\Console\Commands;

use App\Models\Project;
use Illuminate\Console\Command;

class UpdateProjectStatus extends Command
{
    protected $signature = 'projects:update-status';
    protected $description = 'Обновить статус проекта до завершенного, если дата end_date прошла';

    public function handle()
    {
        Project::where('status', 'active')
            ->whereNotNull('end_date')
            ->where('end_date', '<', now())
            ->get()
            ->each(function (Project $project) {
                $project->checkAndUpdateStatus();
            });

        $this->info('Статусы проектов обновлены.');
    }
}
