<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UploadAvatarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'avatar' => [
                'required',
                'image',
                'mimes:jpeg,png,jpg',
                'max:2048',
                'dimensions:min_width=100,min_height=100,max_width=2000,max_height=2000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'avatar.required' => 'Файл аватарки обязателен.',
            'avatar.image' => 'Файл должен быть изображением.',
            'avatar.mimes' => 'Допустимые форматы: jpeg, png, jpg.',
            'avatar.max' => 'Размер файла не должен превышать 2 МБ.',
            'avatar.dimensions' => 'Изображение должно быть не менее 100x100 и не более 2000x2000 пикселей.',
        ];
    }
}
