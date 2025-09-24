<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Throwable;

trait RespondsWithJson
{
    protected function successResponse(mixed $data = null, int $status = 200, array $meta = []): JsonResponse
    {
        $payload = [];

        if (!is_null($data)) {
            $payload['data'] = $data;
        }

        if (!empty($meta)) {
            $payload['meta'] = $meta;
        }

        return response()->json($payload, $status);
    }

    protected function payloadResponse(array $payload, int $status = 200): JsonResponse
    {
        return response()->json($payload, $status);
    }

    protected function messageResponse(string $message, int $status = 200, array $extra = []): JsonResponse
    {
        return response()->json(array_merge(['message' => $message], $extra), $status);
    }

    protected function paginatedResponse(LengthAwarePaginator $paginator, string $resourceClass, int $status = 200, array $meta = []): JsonResponse
    {
        if (!is_subclass_of($resourceClass, JsonResource::class)) {
            throw new InvalidArgumentException(sprintf('%s must extend %s', $resourceClass, JsonResource::class));
        }

        $resourceCollection = $resourceClass::collection($paginator);

        $paginationMeta = [
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
        ];

        return $this->successResponse($resourceCollection, $status, array_merge($paginationMeta, $meta));
    }

    protected function errorResponse(string $message, int $status = 500, array $errors = []): JsonResponse
    {
        $payload = ['message' => $message];

        if (!empty($errors)) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $status);
    }

    protected function guardedOperation(callable $callback, string $logMessage, array $context = [], string $userMessage = 'Произошла ошибка', int $status = 500): JsonResponse
    {
        try {
            return $callback();
        } catch (Throwable $exception) {
            Log::error($logMessage, array_merge($context, ['exception' => $exception]));

            return $this->errorResponse($userMessage, $status);
        }
    }
}
