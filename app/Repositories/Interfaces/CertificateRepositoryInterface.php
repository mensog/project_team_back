<?php

namespace App\Repositories\Interfaces;

use App\Models\Certificate;
use Illuminate\Pagination\LengthAwarePaginator;

interface CertificateRepositoryInterface
{
    public function getUserCertificates(int $userId): LengthAwarePaginator;
    public function create(array $data): Certificate;
    public function delete(int $certificateId): void;
}
