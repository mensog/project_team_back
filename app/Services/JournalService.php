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

    public function getAll(?string $type = null): \Illuminate\Pagination\LengthAwarePaginator
    {
        return $this->journalRepository->getAll($type);
    }

    public function find(int $id): \App\Models\Journal
    {
        return $this->journalRepository->find($id);
    }

    public function create(array $data, int $userId): \App\Models\Journal
    {
        $data['user_id'] = $userId;
        return $this->journalRepository->create($data);
    }

    public function update(int $id, array $data): \App\Models\Journal
    {
        return $this->journalRepository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->journalRepository->delete($id);
    }
}
