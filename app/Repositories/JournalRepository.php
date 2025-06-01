<?php

namespace App\Repositories;

use App\Models\Journal;
use App\Repositories\Interfaces\JournalRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class JournalRepository implements JournalRepositoryInterface
{
    protected $model;

    public function __construct(Journal $model)
    {
        $this->model = $model;
    }

    public function getAll(?string $type = null): LengthAwarePaginator
    {
        $query = $this->model->with('user')->withCount('participants');
        if ($type) {
            $query->where('type', $type);
        }
        return $query->paginate(10);
    }

    public function find(int $id): Journal
    {
        return $this->model->with(['user', 'participants'])->findOrFail($id);
    }

    public function create(array $data): Journal
    {
        $journal = $this->model->create([
            'user_id' => $data['user_id'],
            'title' => $data['title'],
            'type' => $data['type'],
            'date' => $data['date'],
        ]);

        $participants = collect($data['participants'] ?? [])->keyBy('user_id');
        $allUsers = \App\Models\User::pluck('id');
        $syncData = $allUsers->mapWithKeys(function ($userId) use ($participants) {
            return [$userId => ['status' => $participants[$userId]['status'] ?? 'present']];
        })->toArray();

        $journal->participants()->sync($syncData);

        return $journal;
    }

    public function update(int $id, array $data): Journal
    {
        $journal = $this->find($id);
        $journal->update([
            'title' => $data['title'],
            'type' => $data['type'],
            'date' => $data['date'],
        ]);

        if (isset($data['participants'])) {
            $participants = collect($data['participants'])->keyBy('user_id');
            $allUsers = \App\Models\User::pluck('id');
            $syncData = $allUsers->mapWithKeys(function ($userId) use ($participants) {
                return [$userId => ['status' => $participants[$userId]['status'] ?? 'present']];
            })->toArray();
            $journal->participants()->sync($syncData);
        }

        return $journal;
    }

    public function delete(int $id): bool
    {
        $journal = $this->find($id);
        $journal->participants()->detach();
        return $journal->delete();
    }
}
