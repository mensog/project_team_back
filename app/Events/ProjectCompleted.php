<?php

namespace App\Events;

use App\Models\Project;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProjectCompleted
{
    use Dispatchable, SerializesModels;

    public $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }
}
