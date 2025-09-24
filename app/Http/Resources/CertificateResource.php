<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Concerns\ResolvesMediaUrls;

class CertificateResource extends JsonResource
{
    use ResolvesMediaUrls;

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'event_id' => $this->event_id,
            'issued_by' => $this->issued_by,
            'issue_date' => $this->issue_date,
            'file_url' => $this->toPublicUrl($this->file_path),
            'event' => $this->event ? [
                'id' => $this->event->id,
                'title' => $this->event->title,
            ] : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
