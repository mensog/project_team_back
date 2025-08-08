<?php

namespace App\Http\Controllers;

use App\Http\Requests\Certificate\StoreCertificateRequest;
use App\Http\Requests\Certificate\UpdateCertificateRequest;
use App\Http\Resources\CertificateResource;
use App\Services\Interfaces\CertificateServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CertificateController extends Controller
{
    protected $certificateService;

    public function __construct(CertificateServiceInterface $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $userId = $request->query('user_id', $request->user()->id);
            $certificates = $this->certificateService->getCertificatesByUser((int) $userId);
            return response()->json([
                'data' => CertificateResource::collection($certificates),
                'meta' => [
                    'current_page' => $certificates->currentPage(),
                    'last_page' => $certificates->lastPage(),
                    'per_page' => $certificates->perPage(),
                    'total' => $certificates->total(),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error in CertificateController::index', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id,
                'query_user_id' => $request->query('user_id'),
            ]);
            return response()->json(['message' => 'Ошибка при получении сертификатов'], 500);
        }
    }

    public function indexByUser(int $user): JsonResponse
    {
        try {
            $certificates = $this->certificateService->getCertificatesByUser($user);
            return response()->json([
                'data' => CertificateResource::collection($certificates),
                'meta' => [
                    'current_page' => $certificates->currentPage(),
                    'last_page' => $certificates->lastPage(),
                    'per_page' => $certificates->perPage(),
                    'total' => $certificates->total(),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error in CertificateController::indexByUser', [
                'error' => $e->getMessage(),
                'user_id' => $user,
            ]);
            return response()->json(['message' => 'Ошибка при получении сертификатов пользователя'], 500);
        }
    }

    public function store(StoreCertificateRequest $request): JsonResponse
    {
        try {
            $certificate = $this->certificateService->storeCertificate($request->validated(), $request->user()->id);
            return response()->json([
                'message' => 'Сертификат успешно создан!',
                'data' => new CertificateResource($certificate)
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error in CertificateController::store', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id,
            ]);
            return response()->json(['message' => 'Ошибка при создании сертификата'], 500);
        }
    }

    public function update(UpdateCertificateRequest $request, int $id): JsonResponse
    {
        try {
            $certificate = $this->certificateService->updateCertificate($id, $request->validated(), $request->user()->id);
            return response()->json([
                'message' => 'Сертификат успешно обновлён!',
                'data' => new CertificateResource($certificate)
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error in CertificateController::update', [
                'error' => $e->getMessage(),
                'certificate_id' => $id,
                'user_id' => $request->user()->id,
            ]);
            return response()->json(['message' => 'Ошибка при обновлении сертификата'], 500);
        }
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        try {
            $this->certificateService->deleteCertificate($id, $request->user()->id);
            return response()->json([
                'message' => "Сертификат с id:$id успешно удалён!"
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error in CertificateController::destroy', [
                'error' => $e->getMessage(),
                'certificate_id' => $id,
                'user_id' => $request->user()->id,
            ]);
            return response()->json(['message' => 'Ошибка при удалении сертификата'], 500);
        }
    }
}
