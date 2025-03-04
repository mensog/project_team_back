<?php

namespace App\Repositories;

use App\Models\Project;
use App\Repositories\Interfaces\ProjectRepositoryInterface;

class ProjectRepository implements ProjectRepositoryInterface
{
    protected $model;

    public function __construct(Project $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find(int $id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $project = $this->find($id);
        if ($project) {
            $project->update($data);
        }
        return $project;
    }

    public function delete(int $id)
    {
        $project = $this->find($id);
        if ($project) {
            $project->delete();
        }
        return true;
    }
}
