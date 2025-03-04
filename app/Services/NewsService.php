<?php

namespace App\Services;

use App\Repositories\Interfaces\NewsRepositoryInterface;
use App\Services\Interfaces\NewsServiceInterface;

class NewsService implements NewsServiceInterface
{
    protected $newsRepository;

    public function __construct(NewsRepositoryInterface $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    public function all()
    {
        return $this->newsRepository->all();
    }

    public function find(int $id)
    {
        return $this->newsRepository->find($id);
    }

    public function create(array $data)
    {
        return $this->newsRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->newsRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->newsRepository->delete($id);
    }
}
