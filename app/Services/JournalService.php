<?php

namespace App\Services;

use App\Repositories\Interfaces\JournalRepositoryInterface;
use App\Services\Interfaces\JournalServiceInterface;

class JournalService implements JournalServiceInterface
{
    protected $journalRepository;

    public function __construct(JournalRepositoryInterface $journalRepository)
    {
        $this->journalRepository = $journalRepository;
    }

    public function all()
    {
        return $this->journalRepository->all();
    }

    public function find(int $id)
    {
        return $this->journalRepository->find($id);
    }

    public function create(array $data)
    {
        return $this->journalRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->journalRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->journalRepository->delete($id);
    }
}
