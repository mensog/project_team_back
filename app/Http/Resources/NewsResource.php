<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'status' => $this->status,
            'date' => $this->date,
            'type' => $this->type,
            'preview_image' => $this->resolvePreviewImage(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    private function resolvePreviewImage(): ?string
    {
        if (!$this->preview_image) {
            return null;
        }

        if (Str::startsWith($this->preview_image, ['http://', 'https://'])) {
            return $this->preview_image;
        }

        return Storage::disk('public')->url($this->preview_image);
    }
}
