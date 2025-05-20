<?php

namespace App\Repositories;

use App\Models\Journal;
use App\Repositories\Interfaces\JournalRepositoryInterface;

class JournalRepository implements JournalRepositoryInterface
{
    protected $model;

    public function __construct(Journal $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model;
    }

    public function find(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function createMultiple(array $data)
    {
        return $this->model->insert($data);
    }

    public function update(int $id, array $data)
    {
        $journal = $this->find($id);
        $journal->update($data);
        return $journal;
    }

    public function delete(int $id)
    {
        $journal = $this->find($id);
        $journal->delete();
        return true;
    }
}
