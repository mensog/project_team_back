<?php

namespace App\Services;

use App\Models\Event;
use App\Models\User;
use App\Notifications\SendEventCompletedNotification;
use App\Repositories\Interfaces\EventRepositoryInterface;
use App\Services\Interfaces\EventServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;

class EventService implements EventServiceInterface
{
    protected $eventRepository;

    public function __construct(EventRepositoryInterface $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function all(int $perPage = 10): LengthAwarePaginator
    {
        Gate::authorize('viewAny', Event::class);
        return $this->eventRepository->paginate($perPage);
    }

    public function find(int $id)
    {
        $event = $this->eventRepository->find($id);
        Gate::authorize('view', $event);
        return $event;
    }

    public function create(array $data)
    {
        Gate::authorize('create', Event::class);
        return $this->eventRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        $event = $this->eventRepository->find($id);
        Gate::authorize('update', $event);
        return $this->eventRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        $event = $this->eventRepository->find($id);
        Gate::authorize('delete', $event);
        return $this->eventRepository->delete($id);
    }

    public function updateExpiredEventsStatus(): void
    {
        if (!app()->runningInConsole()) {
            Gate::authorize('updateStatus', Event::class);
        }

        $expiredEvents = $this->eventRepository->getExpiredActiveEvents();

        foreach ($expiredEvents as $event) {
            $this->eventRepository->update($event->id, ['status' => 'completed']);

            $participants = $this->eventRepository->getProjectParticipants($event->id);
            $users = User::whereIn('id', $participants)->get();

            if ($users->isNotEmpty()) {
                Notification::send($users, new SendEventCompletedNotification($event));
            }
        }
    }
}
