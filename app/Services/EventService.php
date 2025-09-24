<?php

namespace App\Services;

use App\Models\Event;
use App\Models\User;
use App\Notifications\SendEventCompletedNotification;
use App\Repositories\Interfaces\EventRepositoryInterface;
use App\Services\Interfaces\EventServiceInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

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
        if (!empty($data['preview_image']) && $data['preview_image'] instanceof UploadedFile) {
            $data['preview_image'] = $data['preview_image']->store('event_previews', 'public');
        }
        return $this->eventRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        $event = $this->eventRepository->find($id);
        Gate::authorize('update', $event);
        if (array_key_exists('preview_image', $data)) {
            if ($data['preview_image'] instanceof UploadedFile) {
                if ($event->preview_image) {
                    Storage::disk('public')->delete($event->preview_image);
                }
                $data['preview_image'] = $data['preview_image']->store('event_previews', 'public');
            } elseif (empty($data['preview_image'])) {
                if ($event->preview_image) {
                    Storage::disk('public')->delete($event->preview_image);
                }
                $data['preview_image'] = null;
            }
        }
        return $this->eventRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        $event = $this->eventRepository->find($id);
        Gate::authorize('delete', $event);
        if ($event->preview_image) {
            Storage::disk('public')->delete($event->preview_image);
        }
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
