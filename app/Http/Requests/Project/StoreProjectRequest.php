<?php

namespace App\Http\Requests\Project;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function authorize()
    {
        return true;
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
