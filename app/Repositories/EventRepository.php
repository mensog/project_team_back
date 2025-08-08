<?php

namespace App\Repositories;

use App\Models\Event;
use App\Repositories\Interfaces\EventRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

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

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->paginate($perPage);
    }

    public function find(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $event = $this->find($id);
        $event->update($data);
        return $event;
    }

    public function delete(int $id)
    {
        $event = $this->find($id);
        return $event->delete();
    }

    public function getExpiredActiveEvents(): array
    {
        return $this->model->where('status', 'active')
            ->where('end_date', '<=', Carbon::now())
            ->get()
            ->all();
    }

    public function getProjectParticipants(int $eventId): array
    {
        $event = $this->find($eventId);
        if ($event->project_id) {
            return $event->project->participants()->pluck('users.id')->toArray();
        }
        return [];
    }
}
