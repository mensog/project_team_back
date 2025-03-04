<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Event;

class StoreEventRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('create', Event::class);
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
