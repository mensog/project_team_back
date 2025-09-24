<?php

namespace App\Http\Controllers;

use App\Http\Requests\Journal\StoreJournalRequest;
use App\Http\Requests\Journal\UpdateJournalRequest;
use App\Http\Resources\JournalResource;
use App\Services\Interfaces\JournalServiceInterface;
use Illuminate\Http\JsonResponse;

class JournalController extends Controller
{
    protected JournalServiceInterface $journalService;

    public function __construct(JournalServiceInterface $journalService)
    {
        $this->journalService = $journalService;
    }

    public function index(): JsonResponse
    {
        $type = request()->query('type');

        return $this->guardedOperation(
            fn () => $this->paginatedResponse(
                $this->journalService->getAll($type),
                JournalResource::class
            ),
            'Error retrieving journals',
            ['type' => $type],
            'Ошибка при получении журналов'
        );
    }

    public function show(int $id): JsonResponse
    {
        return $this->guardedOperation(
            fn () => $this->successResponse(new JournalResource($this->journalService->find($id))),
            'Error retrieving journal',
            ['journal_id' => $id],
            'Журнал не найден',
            404
        );
    }

    public function store(StoreJournalRequest $request): JsonResponse
    {
        return $this->guardedOperation(
            fn () => $this->messageResponse('Журнал создан!', 201, [
                'data' => new JournalResource(
                    $this->journalService->create($request->validated(), $request->user()->id)
                ),
            ]),
            'Error creating journal',
            ['user_id' => $request->user()->id],
            'Ошибка при создании журнала'
        );
    }

    public function update(UpdateJournalRequest $request, int $id): JsonResponse
    {
        return $this->guardedOperation(
            fn () => $this->messageResponse('Журнал обновлён!', 200, [
                'data' => new JournalResource(
                    $this->journalService->update($id, $request->validated())
                ),
            ]),
            'Error updating journal',
            ['journal_id' => $id],
            'Ошибка при обновлении журнала'
        );
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->guardedOperation(
            function () use ($id) {
                $this->journalService->delete($id);

                return $this->messageResponse('Журнал удалён!');
            },
            'Error deleting journal',
            ['journal_id' => $id],
            'Ошибка при удалении журнала'
        );
    }
}
