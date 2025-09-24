<?php

namespace App\Http\Controllers;

use App\Http\Requests\Certificate\StoreCertificateRequest;
use App\Http\Requests\Certificate\UpdateCertificateRequest;
use App\Http\Resources\CertificateResource;
use App\Services\Interfaces\CertificateServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    protected CertificateServiceInterface $certificateService;

    public function __construct(CertificateServiceInterface $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    public function index(Request $request): JsonResponse
    {
        $userId = (int) $request->query('user_id', $request->user()->id);

        return $this->guardedOperation(
            fn () => $this->paginatedResponse(
                $this->certificateService->getCertificatesByUser($userId),
                CertificateResource::class
            ),
            'Error retrieving certificates list',
            [
                'user_id' => $request->user()->id,
                'query_user_id' => $userId,
            ],
            'Ошибка при получении сертификатов'
        );
    }

    public function indexByUser(int $user): JsonResponse
    {
        return $this->guardedOperation(
            fn () => $this->paginatedResponse(
                $this->certificateService->getCertificatesByUser($user),
                CertificateResource::class
            ),
            'Error retrieving certificates for user',
            ['user_id' => $user],
            'Ошибка при получении сертификатов пользователя'
        );
    }

    public function store(StoreCertificateRequest $request): JsonResponse
    {
        return $this->guardedOperation(
            fn () => $this->messageResponse(
                'Сертификат успешно создан!',
                201,
                [
                    'data' => new CertificateResource(
                        $this->certificateService->storeCertificate($request->validated(), $request->user()->id)
                    ),
                ]
            ),
            'Error creating certificate',
            ['user_id' => $request->user()->id],
            'Ошибка при создании сертификата'
        );
    }

    public function update(UpdateCertificateRequest $request, int $id): JsonResponse
    {
        return $this->guardedOperation(
            fn () => $this->messageResponse(
                'Сертификат успешно обновлён!',
                200,
                [
                    'data' => new CertificateResource(
                        $this->certificateService->updateCertificate($id, $request->validated(), $request->user()->id)
                    ),
                ]
            ),
            'Error updating certificate',
            [
                'certificate_id' => $id,
                'user_id' => $request->user()->id,
            ],
            'Ошибка при обновлении сертификата'
        );
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        return $this->guardedOperation(
            function () use ($request, $id) {
                $this->certificateService->deleteCertificate($id, $request->user()->id);

                return $this->messageResponse("Сертификат с id:$id успешно удалён!");
            },
            'Error deleting certificate',
            [
                'certificate_id' => $id,
                'user_id' => $request->user()->id,
            ],
            'Ошибка при удалении сертификата'
        );
    }
}
