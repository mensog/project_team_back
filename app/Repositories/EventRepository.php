<?php

namespace App\Repositories;

use App\Models\Event;
use App\Repositories\Interfaces\EventRepositoryInterface;

class EventRepository implements EventRepositoryInterface
{
    protected $model;

    public function __construct(Event $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find(int $id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $event = $this->find($id);
        if ($event) {
            $event->update($data);
        }
        return $event;
    }

    public function delete(int $id)
    {
        $event = $this->find($id);
        if ($event) {
            $event->delete();
        }
        return true;
    }
}
