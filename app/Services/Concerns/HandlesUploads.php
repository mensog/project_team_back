<?php

namespace App\Services\Concerns;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HandlesUploads
{
    protected function storePublicFile(UploadedFile $file, string $directory): string
    {
        return $file->store($directory, 'public');
    }

    protected function deletePublicFile(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    protected function syncUpload(array &$payload, string $key, ?string $currentPath, string $directory): void
    {
        if (!array_key_exists($key, $payload)) {
            return;
        }

        $value = $payload[$key];

        if ($value instanceof UploadedFile) {
            $this->deletePublicFile($currentPath);
            $payload[$key] = $this->storePublicFile($value, $directory);

            return;
        }

        if ($value === null) {
            $this->deletePublicFile($currentPath);
            $payload[$key] = null;

            return;
        }

        unset($payload[$key]);
    }
}
