<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class JournalResource extends JsonResource
{
    public function toArray($request): array
    {
        $allUsers = User::all();
        $currentParticipants = $this->whenLoaded('participants', function () {
            return $this->participants->pluck('pivot.status', 'id')->toArray();
        }, []);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'type' => $this->type,
            'date' => $this->date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user_created' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'full_name' => trim("{$this->user->last_name} {$this->user->first_name} {$this->user->middle_name}"),
                ];
            }),
            'participants' => $allUsers->map(function ($user) use ($currentParticipants) {
                return [
                    'id' => $user->id,
                    'full_name' => trim("{$user->last_name} {$user->first_name} {$user->middle_name}"),
                    'status' => $currentParticipants[$user->id] ?? 'absent',
                ];
            })->values(),
        ];
    }
}
