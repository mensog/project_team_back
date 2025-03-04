<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Project;

class UpdateProjectRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('update', $this->project);
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'certificate' => 'nullable|string|max:255',
            'status' => 'required|string|in:active,completed',
            'user_id' => 'nullable|exists:users,id',
        ];
    }
}
