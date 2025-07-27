<?php

namespace App\Http\Controllers;

use App\Http\Requests\Journal\StoreJournalRequest;
use App\Http\Requests\Journal\UpdateJournalRequest;
use App\Http\Resources\JournalResource;
use App\Services\Interfaces\JournalServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class JournalController extends Controller
{
    protected $journalService;

    public function __construct(JournalServiceInterface $journalService)
    {
        $this->journalService = $journalService;
    }

    public function index(): JsonResponse
    {
        try {
            $type = request()->query('type');
            $journals = $this->journalService->getAll($type);
            return response()->json([
                'data' => JournalResource::collection($journals),
                'meta' => [
                    'current_page' => $journals->currentPage(),
                    'last_page' => $journals->lastPage(),
                    'per_page' => $journals->perPage(),
                    'total' => $journals->total(),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error in JournalController::index', ['error' => $e->getMessage(), 'type' => request()->query('type')]);
            return response()->json(['message' => 'Ошибка при получении журналов'], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $journal = $this->journalService->find($id);
            return response()->json([
                'data' => new JournalResource($journal),
            ]);
        } catch (\Exception $e) {
            Log::error('Error in JournalController::show', ['error' => $e->getMessage(), 'id' => $id]);
            return response()->json(['message' => 'Журнал не найден'], 404);
        }
    }

    public function store(StoreJournalRequest $request): JsonResponse
    {
        try {
            $journal = $this->journalService->create($request->validated(), $request->user()->id);
            return response()->json([
                'message' => 'Журнал создан!',
                'data' => new JournalResource($journal),
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error in JournalController::store', ['error' => $e->getMessage(), 'user_id' => $request->user()->id]);
            return response()->json(['message' => 'Ошибка при создании журнала'], 500);
        }
    }

    public function update(UpdateJournalRequest $request, int $id): JsonResponse
    {
        try {
            $journal = $this->journalService->update($id, $request->validated());
            return response()->json([
                'message' => 'Журнал обновлён!',
                'data' => new JournalResource($journal),
            ]);
        } catch (\Exception $e) {
            Log::error('Error in JournalController::update', ['error' => $e->getMessage(), 'id' => $id]);
            return response()->json(['message' => 'Ошибка при обновлении журнала'], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->journalService->delete($id);
            return response()->json([
                'message' => 'Журнал удалён!',
            ]);
        } catch (\Exception $e) {
            Log::error('Error in JournalController::destroy', ['error' => $e->getMessage(), 'id' => $id]);
            return response()->json(['message' => 'Ошибка при удалении журнала'], 500);
        }
    }
}
