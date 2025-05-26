<?php

namespace App\Http\Controllers;

use App\Http\Requests\Certificate\StoreCertificateRequest;
use App\Http\Resources\CertificateResource;
use App\Services\Interfaces\CertificateServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    protected $certificateService;

    public function __construct(CertificateServiceInterface $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    public function index(Request $request): JsonResponse
    {
        $certificates = $this->certificateService->getUserCertificates($request->user()->id);
        return response()->json([
            'data' => CertificateResource::collection($certificates)
        ]);
    }

    public function indexByUser(int $userId): JsonResponse
    {
        $certificates = $this->certificateService->getCertificatesByUser($userId);
        return response()->json([
            'data' => CertificateResource::collection($certificates)
        ]);
    }

    public function store(StoreCertificateRequest $request): JsonResponse
    {
        $certificate = $this->certificateService->storeCertificate($request->validated(), $request->user()->id);
        return response()->json([
            'message' => 'Сертификат создан!',
            'data' => new CertificateResource($certificate)
        ], 201);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $this->certificateService->deleteCertificate($id, $request->user()->id);
        return response()->json([
            'message' => "Сертификат с id:$id удален!"
        ], 200);
    }
}
