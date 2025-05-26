<?php

namespace App\Services\Interfaces;

use App\Models\Certificate;
use Illuminate\Pagination\LengthAwarePaginator;

interface CertificateServiceInterface
{
    public function getUserCertificates(int $userId): LengthAwarePaginator;
    public function getCertificatesByUser(int $userId): LengthAwarePaginator;
    public function storeCertificate(array $data, int $userId): Certificate;
    public function deleteCertificate(int $certificateId, int $userId): void;
}
