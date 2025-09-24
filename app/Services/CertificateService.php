<?php

namespace App\Services;

use App\Models\Certificate;
use App\Repositories\Interfaces\CertificateRepositoryInterface;
use App\Services\Interfaces\CertificateServiceInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class CertificateService implements CertificateServiceInterface
{
    protected $certificateRepository;

    public function __construct(CertificateRepositoryInterface $certificateRepository)
    {
        $this->certificateRepository = $certificateRepository;
    }

    public function getUserCertificates(int $userId): LengthAwarePaginator
    {
        Gate::authorize('viewAny', Certificate::class);
        return $this->certificateRepository->getUserCertificates($userId);
    }

    public function getCertificatesByUser(int $userId): LengthAwarePaginator
    {
        Gate::authorize('viewAnyForUser', [Certificate::class, $userId]);
        return $this->certificateRepository->getUserCertificates($userId);
    }

    public function storeCertificate(array $data, int $userId): Certificate
    {
        Gate::authorize('create', Certificate::class);

        $filePath = $data['file']->store("certificates/{$userId}", 'public');

        $certificateData = [
            'user_id' => $userId,
            'event_id' => $data['event_id'] ?? null,
            'file_path' => $filePath,
            'issued_by' => $data['issued_by'],
            'issue_date' => $data['issue_date'],
        ];

        return $this->certificateRepository->create($certificateData);
    }

    public function updateCertificate(int $certificateId, array $data, int $userId): Certificate
    {
        $certificate = Certificate::findOrFail($certificateId);
        Gate::authorize('update', $certificate);

        $certificateData = [
            'issued_by' => $data['issued_by'] ?? $certificate->issued_by,
            'issue_date' => $data['issue_date'] ?? $certificate->issue_date,
        ];

        if (array_key_exists('event_id', $data)) {
            $certificateData['event_id'] = $data['event_id'];
        }

        if (isset($data['file'])) {
            if ($certificate->file_path && Storage::disk('public')->exists($certificate->file_path)) {
                Storage::disk('public')->delete($certificate->file_path);
            }
            $certificateData['file_path'] = $data['file']->store("certificates/{$userId}", 'public');
        }

        $certificate->update($certificateData);
        return $certificate->load('event');
    }

    public function deleteCertificate(int $certificateId, int $userId): void
    {
        $certificate = Certificate::findOrFail($certificateId);
        Gate::authorize('delete', $certificate);

        if ($certificate->file_path && Storage::disk('public')->exists($certificate->file_path)) {
            Storage::disk('public')->delete($certificate->file_path);
        }
        $this->certificateRepository->delete($certificateId);
    }
}
