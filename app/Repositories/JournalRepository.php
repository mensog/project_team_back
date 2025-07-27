<?php

namespace App\Repositories;

use App\Models\Journal;
use App\Repositories\Interfaces\JournalRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class JournalRepository implements JournalRepositoryInterface
{
    protected $model;

    public function __construct(Journal $model)
    {
        $this->model = $model;
    }

    public function getAll(?string $type = null): LengthAwarePaginator
    {
        try {
            $query = $this->model->with(['user', 'participants']);
            if ($type) {
                $query->where('type', $type);
            }
            return $query->paginate(10);
        } catch (\Exception $e) {
            Log::error('Ошибка в JournalRepository::getAll', ['error' => $e->getMessage(), 'type' => $type]);
            throw $e;
        }
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

        if (isset($data['participants'])) {
            $syncData = collect($data['participants'])->mapWithKeys(function ($participant) {
                return [$participant['user_id'] => ['status' => $participant['status']]];
            })->toArray();
            $journal->participants()->sync($syncData);
        }

        return $journal->load(['user', 'participants']);
    }

    public function update(int $id, array $data): Journal
    {
        $journal = $this->find($id);
        $journal->update([
            'title' => $data['title'] ?? $journal->title,
            'type' => $data['type'] ?? $journal->type,
            'date' => $data['date'] ?? $journal->date,
        ]);

        if (isset($data['participants'])) {
            $syncData = collect($data['participants'])->mapWithKeys(function ($participant) {
                return [$participant['user_id'] => ['status' => $participant['status']]];
            })->toArray();
            $journal->participants()->sync($syncData);
        }

        return $journal->load(['user', 'participants']);
    }

    public function delete(int $id): bool
    {
        $journal = $this->find($id);
        $journal->participants()->detach();
        return $journal->delete();
    }
}
