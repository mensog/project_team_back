<?php

namespace App\Repositories;

use App\Models\News;
use App\Repositories\Interfaces\NewsRepositoryInterface;

class NewsRepository implements NewsRepositoryInterface
{
    protected $model;

    public function __construct(News $model)
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
        $news = $this->find($id);
        if ($news) {
            $news->update($data);
        }
        return $news;
    }

    public function delete(int $id)
    {
        $news = $this->find($id);
        if ($news) {
            $news->delete();
        }
        return true;
    }
}
