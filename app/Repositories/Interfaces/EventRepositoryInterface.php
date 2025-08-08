<?php

namespace App\Repositories\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;

interface EventRepositoryInterface
{
    public function all();
    public function paginate(int $perPage = 10): LengthAwarePaginator;
    public function find(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function getExpiredActiveEvents(): array;
    public function getProjectParticipants(int $eventId): array;
}
