<?php

namespace App\Services;

use App\Models\Certificate;
use App\Repositories\Interfaces\CertificateRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Pagination\LengthAwarePaginator;

class CertificateService implements Interfaces\CertificateServiceInterface
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

        $certificate = $this->certificateRepository->create($certificateData);

        return $certificate;
    }

    public function deleteCertificate(int $certificateId, int $userId): void
    {
        $certificate = Certificate::findOrFail($certificateId);
        Gate::authorize('delete', $certificate);

        Storage::disk('public')->delete($certificate->file_path);
        $this->certificateRepository->delete($certificateId);
    }
}
