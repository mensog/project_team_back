<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'preview_image' => $this->preview_image,
            'certificate' => $this->certificate,
            'status' => $this->status,
            'user_id' => $this->user_id,
            'participants' => $this->participants->pluck('id'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
