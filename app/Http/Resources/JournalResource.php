<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JournalResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'action' => $this->action,
            'user_id' => $this->user_id,
            'date' => $this->date,
            'status' => $this->status,
            'participant_id' => $this->participant_id,
            'participant' => $this->whenLoaded('participant', function () {
                return [
                    'id' => $this->participant->id,
                    'first_name' => $this->participant->first_name,
                    'last_name' => $this->participant->last_name,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
