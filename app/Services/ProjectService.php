<?php

namespace App\Services;

use App\Repositories\Interfaces\ProjectRepositoryInterface;
use App\Services\Interfaces\ProjectServiceInterface;

class ProjectService implements ProjectServiceInterface
{
    protected $projectRepository;

    public function __construct(ProjectRepositoryInterface $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function all()
    {
        return $this->projectRepository->all();
    }

    public function find(int $id)
    {
        return $this->projectRepository->find($id);
    }

    public function create(array $data)
    {
        return $this->projectRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->projectRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->projectRepository->delete($id);
    }
}
