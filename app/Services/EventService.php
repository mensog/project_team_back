<?php

namespace App\Services;

use App\Repositories\Interfaces\EventRepositoryInterface;
use App\Services\Interfaces\EventServiceInterface;

class EventService implements EventServiceInterface
{
    protected $eventRepository;

    public function __construct(EventRepositoryInterface $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function all()
    {
        return $this->eventRepository->all();
    }

    public function find(int $id)
    {
        return $this->eventRepository->find($id);
    }

    public function create(array $data)
    {
        return $this->eventRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->eventRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->eventRepository->delete($id);
    }
}
