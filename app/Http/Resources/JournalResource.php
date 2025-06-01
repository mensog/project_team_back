<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JournalResource extends JsonResource
{
    public function toArray($request)
    {
        $participants = $this->participants()->paginate(10);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'type' => $this->type,
            'date' => $this->date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'full_name' => trim("{$this->user->last_name} {$this->user->first_name} {$this->user->middle_name}"),
                ];
            }),
            'participants' => [
                'data' => $participants->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'full_name' => trim("{$user->last_name} {$user->first_name} {$user->middle_name}"),
                        'status' => $user->pivot->status,
                    ];
                }),
                'meta' => [
                    'current_page' => $participants->currentPage(),
                    'last_page' => $participants->lastPage(),
                    'total' => $participants->total(),
                ],
            ],
        ];
    }
}
