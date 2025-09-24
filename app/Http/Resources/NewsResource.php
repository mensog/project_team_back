<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Concerns\ResolvesMediaUrls;

class NewsResource extends JsonResource
{
    use ResolvesMediaUrls;

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'status' => $this->status,
            'date' => $this->date,
            'type' => $this->type,
            'preview_image' => $this->toPublicUrl($this->preview_image),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
