<?php

namespace App\Repositories;

use App\Models\News;
use App\Repositories\Interfaces\NewsRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class NewsRepository implements NewsRepositoryInterface
{
    protected $model;

    public function __construct(News $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->paginate($perPage);
    }

    public function byStatus(string $status, int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->where('status', $status)->paginate($perPage);
    }

    public function find(int $id): News
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data): News
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): News
    {
        $news = $this->find($id);
        $news->update($data);
        return $news;
    }

    public function delete(int $id): bool
    {
        $news = $this->find($id);
        return $news->delete();
    }
}
