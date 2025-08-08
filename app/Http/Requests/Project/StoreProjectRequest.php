<?php

namespace App\Http\Requests\Project;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'preview_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'certificate' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ];

        if ($this->user()->is_admin) {
            $rules['status'] = ['nullable', 'in:active,completed'];
            $rules['participants'] = ['nullable', 'array'];
            $rules['participants.*'] = ['exists:users,id'];
        }

        return $rules;
    }
}
