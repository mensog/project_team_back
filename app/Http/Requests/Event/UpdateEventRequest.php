<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Event;

class UpdateEventRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'date' => 'required|datetime',
            'status' => 'required|string|in:active,completed',
            'description' => 'nullable|string',
            'project_id' => 'nullable|exists:projects,id',
        ];
    }
}
