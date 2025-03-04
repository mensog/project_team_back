<?php

namespace App\Http\Requests\Rating;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Rating;

class StoreRatingRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('create', Rating::class);
    }

    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'event_id' => 'required|exists:events,id',
            'points' => 'required|integer|min:0',
        ];
    }
}
