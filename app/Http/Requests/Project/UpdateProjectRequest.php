<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Project;

class UpdateProjectRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:512',
            'preview_image' => ['nullable', 'image', 'mimes:jpeg,png,gif,webp', 'max:10240'],
            'certificate' => ['nullable', 'file', 'mimes:pdf,jpeg,png', 'max:10240'],
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
        ];

        if ($this->user()->is_admin) {
            $rules['status'] = 'required|string|in:active,completed';
            $rules['user_id'] = 'nullable|exists:users,id';
            $rules['participants'] = 'nullable|array';
            $rules['participants.*'] = 'exists:users,id';
        }

        return $rules;
    }
}
