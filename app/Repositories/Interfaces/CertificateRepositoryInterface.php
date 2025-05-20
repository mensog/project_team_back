<?php

namespace App\Repositories\Interfaces;

interface CertificateRepositoryInterface
{
    public function getUserCertificates(int $userId): array;
    public function create(array $data): array;
    public function delete(int $certificateId): void;
}
