<?php

namespace App\Services\Interfaces;

use App\Models\Journal;
use Illuminate\Pagination\LengthAwarePaginator;

interface JournalServiceInterface
{
    public function getAll(?string $type = null): LengthAwarePaginator;
    public function find(int $id): Journal;
    public function create(array $data, int $userId): Journal;
    public function update(int $id, array $data): Journal;
    public function delete(int $id): bool;
}
