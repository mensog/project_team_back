<?php

namespace App\Services;

use App\Models\Journal;
use App\Repositories\Interfaces\JournalRepositoryInterface;
use App\Services\Interfaces\JournalServiceInterface;
use Illuminate\Support\Facades\Gate;

class JournalService implements JournalServiceInterface
{
    protected $journalRepository;

    public function __construct(JournalRepositoryInterface $journalRepository)
    {
        $this->journalRepository = $journalRepository;
    }

    public function getAll(?string $type = null): \Illuminate\Pagination\LengthAwarePaginator
    {
        Gate::authorize('viewAny', Journal::class);
        return $this->journalRepository->getAll($type);
    }

    public function find(int $id): \App\Models\Journal
    {
        $journal = $this->journalRepository->find($id);
        Gate::authorize('view', $journal);
        return $journal;
    }

    public function create(array $data, int $userId): \App\Models\Journal
    {
        Gate::authorize('create', Journal::class);
        $data['user_id'] = $userId;
        return $this->journalRepository->create($data);
    }

    public function update(int $id, array $data): \App\Models\Journal
    {
        $journal = $this->journalRepository->find($id);
        Gate::authorize('update', $journal);
        return $this->journalRepository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        $journal = $this->journalRepository->find($id);
        Gate::authorize('delete', $journal);
        return $this->journalRepository->delete($id);
    }
}
