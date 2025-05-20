<?php

namespace App\Services\Interfaces;

interface CertificateServiceInterface
{
    public function getUserCertificates(int $userId): array;
    public function storeCertificate(array $data, int $userId): array;
    public function deleteCertificate(int $certificateId, int $userId): void;
}
