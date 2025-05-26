<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CertificateResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'event_id' => $this->event_id,
            'issued_by' => $this->issued_by,
            'issue_date' => $this->issue_date,
            'file_url' => Storage::disk('public')->url($this->file_path),
            'event' => $this->event ? [
                'id' => $this->event->id,
                'title' => $this->event->title,
            ] : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
