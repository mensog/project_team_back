<?php

namespace App\Services\Interfaces;

use App\Models\News;
use Illuminate\Support\Collection;

interface NewsServiceInterface
{
    public function all(): Collection;
    public function find(int $id): News;
    public function create(array $data): News;
    public function update(int $id, array $data): News;
    public function delete(int $id): void;
    public function byStatus(string $status): Collection;
}
