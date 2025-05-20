<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Event;
use App\Repositories\Interfaces\CertificateRepositoryInterface;
use App\Services\Interfaces\JournalServiceInterface;
use Illuminate\Support\Facades\Storage;

class CertificateService implements Interfaces\CertificateServiceInterface
{
    protected $certificateRepository;
    protected $journalService;

    public function __construct(
        CertificateRepositoryInterface $certificateRepository,
        JournalServiceInterface $journalService
    ) {
        $this->certificateRepository = $certificateRepository;
        $this->journalService = $journalService;
    }

    public function getUserCertificates(int $userId): array
    {
        return $this->certificateRepository->getUserCertificates($userId);
    }

    public function storeCertificate(array $data, int $userId): array
    {
        $event = Event::findOrFail($data['event_id']);
        $user = auth()->user();

        // проверка: участвует ли пользователь в эвенте
        if (!$user->events()->where('events.id', $event->id)->exists()) {
            throw new \Exception('You are not participating in this event', 403);
        }

        $filePath = $data['file']->store('certificates', 'public');

        $certificate = $this->certificateRepository->create([
            'user_id' => $userId,
            'event_id' => $data['event_id'],
            'file_path' => $filePath,
        ]);

        $this->journalService->create([
            'user_id' => $userId,
            'action' => "Uploaded certificate for event {$event->title}",
        ]);

        return $certificate;
    }

    public function deleteCertificate(int $certificateId, int $userId): void
    {
        $certificate = Certificate::findOrFail($certificateId);

        if ($certificate->user_id !== $userId && !auth()->user()->is_admin) {
            throw new \Exception('Unauthorized', 403);
        }

        $this->journalService->create([
            'user_id' => $userId,
            'action' => "Deleted certificate ID {$certificateId}",
        ]);

        Storage::disk('public')->delete($certificate->file_path);
        $this->certificateRepository->delete($certificateId);
    }
}
