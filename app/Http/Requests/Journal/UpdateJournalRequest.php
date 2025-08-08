<?php

namespace App\Http\Requests\Journal;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateJournalRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'type' => ['sometimes', Rule::in(['event', 'meeting'])],
            'date' => 'sometimes|date',
            'participants' => 'nullable|array',
            'participants.*.user_id' => 'required|exists:users,id|distinct',
            'participants.*.status' => ['required', Rule::in(['present', 'absent'])],
        ];
    }

    public function messages(): array
    {
        return [
            'participants.*.user_id.exists' => 'Пользователь с ID :input не найден.',
        ];
    }
}
