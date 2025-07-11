<?php

namespace App\Http\Requests\Rating;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Rating;

class UpdateRatingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => 'nullable|exists:users,id',
            'event_id' => 'nullable|exists:events,id',
            'points' => 'nullable|integer|min:0',
        ];
    }
}
