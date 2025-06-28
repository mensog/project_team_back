<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Event;

class StoreEventRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'date' => 'required|date_format:Y-m-d H:i:s',
            'status' => 'required|string|in:active,completed',
            'description' => 'nullable|string',
            'project_id' => 'nullable|exists:projects,id',
        ];
    }
}
