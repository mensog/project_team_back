<?php

namespace App\Http\Requests\News;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\News;

class UpdateNewsRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('update', $this->route('news'));
    }

    public function rules()
    {
        return [
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'status' => 'sometimes|string|in:active,completed',
            'date' => 'sometimes|date',
            'type' => 'sometimes|string|in:active,completed',
        ];
    }
}
