<?php

namespace App\Http\Controllers;

use App\Http\Requests\Journal\StoreJournalRequest;
use App\Http\Requests\Journal\UpdateJournalRequest;
use App\Http\Resources\JournalResource;
use App\Services\Interfaces\JournalServiceInterface;
use Illuminate\Http\JsonResponse;

class JournalController extends Controller
{
    protected $journalService;

    public function __construct(JournalServiceInterface $journalService)
    {
        $this->journalService = $journalService;
    }

    public function index(): JsonResponse
    {
        $type = request()->query('type');
        $journals = $this->journalService->getAll($type);
        return response()->json([
            'data' => JournalResource::collection($journals),
            'meta' => [
                'current_page' => $journals->currentPage(),
                'last_page' => $journals->lastPage(),
                'total' => $journals->total(),
            ],
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $journal = $this->journalService->find($id);
        return response()->json([
            'data' => new JournalResource($journal),
        ]);
    }

    public function store(StoreJournalRequest $request): JsonResponse
    {
        $journal = $this->journalService->create($request->validated(), $request->user()->id);
        return response()->json([
            'message' => 'Журнал создан!',
            'data' => new JournalResource($journal),
        ], 201);
    }

    public function update(UpdateJournalRequest $request, int $id): JsonResponse
    {
        $journal = $this->journalService->update($id, $request->validated());
        return response()->json([
            'message' => 'Журнал обновлён!',
            'data' => new JournalResource($journal),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->journalService->delete($id);
        return response()->json([
            'message' => 'Журнал удалён!',
        ]);
    }
}
