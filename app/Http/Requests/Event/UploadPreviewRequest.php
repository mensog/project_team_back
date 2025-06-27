<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class UploadPreviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'preview_image' => ['required', 'image', 'mimes:jpeg,png,gif,webp', 'max:10240'],
        ];
    }
}
