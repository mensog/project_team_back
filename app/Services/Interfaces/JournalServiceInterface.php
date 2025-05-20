<?php

namespace App\Services\Interfaces;

interface JournalServiceInterface
{
    public function all();
    public function find(int $id);
    public function create(array $data);
    public function createMultiple(array $data, int $userId);
    public function update(int $id, array $data);
    public function delete(int $id);
}
