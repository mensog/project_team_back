<?php

namespace App\Http\Resources\Concerns;

use Illuminate\Support\Facades\Storage;

trait ResolvesMediaUrls
{
    protected function toPublicUrl(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }
}
