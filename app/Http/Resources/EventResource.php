<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Concerns\ResolvesMediaUrls;

class EventResource extends JsonResource
{
    use ResolvesMediaUrls;

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'preview_image' => $this->toPublicUrl($this->preview_image),
            'description' => $this->description,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'project_id' => $this->project_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
