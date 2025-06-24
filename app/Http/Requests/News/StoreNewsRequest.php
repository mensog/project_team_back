<?php

namespace App\Http\Requests\News;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\News;

class StoreNewsRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('create', News::class);
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|string|in:active,completed',
            'date' => 'required|date',
            'type' => 'required|string|in:active,completed',
        ];
    }
}
