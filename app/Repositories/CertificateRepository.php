<?php

namespace App\Repositories;

use App\Models\Certificate;

class CertificateRepository implements Interfaces\CertificateRepositoryInterface
{
    public function getUserCertificates(int $userId): array
    {
        return Certificate::where('user_id', $userId)->with('event')->get()->toArray();
    }

    public function create(array $data): array
    {
        $certificate = Certificate::create($data);
        return $certificate->load('event')->toArray();
    }

    public function delete(int $certificateId): void
    {
        $certificate = Certificate::findOrFail($certificateId);
        $certificate->delete();
    }
}
