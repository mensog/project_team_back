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
        $journal = $this->find($id);
        if ($journal) {
            $journal->update($data);
        }
        return $journal;
    }

    public function delete(int $id)
    {
        $journal = $this->find($id);
        if ($journal) {
            $journal->delete();
        }
        return true;
    }
}
