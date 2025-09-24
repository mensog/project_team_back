<?php

namespace App\Services;

use App\Models\Certificate;
use App\Repositories\Interfaces\CertificateRepositoryInterface;
use App\Services\Concerns\HandlesUploads;
use App\Services\Interfaces\CertificateServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;

class CertificateService implements CertificateServiceInterface
{
    use HandlesUploads;

    protected CertificateRepositoryInterface $certificateRepository;

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

        $filePath = $this->storePublicFile($data['file'], "certificates/{$userId}");

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

        if (!empty($data['file'])) {
            $this->deletePublicFile($certificate->file_path);
            $certificateData['file_path'] = $this->storePublicFile($data['file'], "certificates/{$userId}");
        }

        $certificate->update($certificateData);
        return $certificate->load('event');
    }

    public function deleteCertificate(int $certificateId, int $userId): void
    {
        $certificate = Certificate::findOrFail($certificateId);
        Gate::authorize('delete', $certificate);

        $this->deletePublicFile($certificate->file_path);
        $this->certificateRepository->delete($certificateId);
    }
}
