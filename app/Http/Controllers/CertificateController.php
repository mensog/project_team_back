<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCertificateRequest;
use App\Http\Resources\CertificateResource;
use App\Services\Interfaces\CertificateServiceInterface;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    protected $certificateService;

    public function __construct(CertificateServiceInterface $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $certificates = $this->certificateService->getUserCertificates($request->user()->id);
        return CertificateResource::collection($certificates);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCertificateRequest $request)
    {
        $certificateData = $request->validated();
        $certificateData['file'] = $request->file('file');
        $certificate = $this->certificateService->storeCertificate($certificateData, $request->user()->id);

        return new CertificateResource($certificate);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $this->certificateService->deleteCertificate($id, $request->user()->id);
        return response()->json(null, 204);
    }
}
