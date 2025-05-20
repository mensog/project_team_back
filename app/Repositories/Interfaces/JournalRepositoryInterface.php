<?php

namespace App\Repositories\Interfaces;

interface JournalRepositoryInterface
{
    public function all();
    public function find(int $id);
    public function create(array $data);
    public function createMultiple(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}
