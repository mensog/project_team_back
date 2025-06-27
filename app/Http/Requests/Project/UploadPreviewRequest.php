<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class UploadPreviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'preview_image' => ['required', 'image', 'mimes:jpeg,png,gif,webp', 'max:10240'],
        ];
    }
}
