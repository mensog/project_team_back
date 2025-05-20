<?php

namespace App\Http\Requests\News;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\News;

class UpdateNewsRequest extends FormRequest
{
    public function authorize()
    {
        // return $this->user()->can('update', $this->news);
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|string|in:active,completed',
            'date' => 'required|datetime',
            'type' => 'required|string|in:active,completed',
        ];
    }
}
