<?php

namespace App\Repositories;

use App\Models\Certificate;
use Illuminate\Pagination\LengthAwarePaginator;

class CertificateRepository implements Interfaces\CertificateRepositoryInterface
{
    public function getUserCertificates(int $userId): LengthAwarePaginator
    {
        return Certificate::where('user_id', $userId)->with('event')->paginate(10);
    }

    public function create(array $data): Certificate
    {
        $certificate = Certificate::create($data);
        return $certificate->load('event');
    }

    public function delete(int $certificateId): void
    {
        $certificate = Certificate::findOrFail($certificateId);
        $certificate->delete();
    }
}
