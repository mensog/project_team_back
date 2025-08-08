<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProjectResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'preview_image' => $this->preview_image ? Storage::disk('public')->url($this->preview_image) : null,
            'certificate' => $this->certificate ? Storage::disk('public')->url($this->certificate) : null,
            'status' => $this->status,
            'user_id' => $this->user_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'is_approved' => $this->is_approved,
            'participants' => $this->participants->pluck('id')->toArray(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
