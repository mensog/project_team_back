<?php

namespace App\Http\Requests\Journal;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateJournalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->is_admin;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'type' => ['required', Rule::in(['event', 'meeting'])],
            'date' => 'required|date',
            'participants' => 'sometimes|array',
            'participants.*.user_id' => 'required|exists:users,id|distinct',
            'participants.*.status' => ['required', Rule::in(['present', 'absent'])],
        ];
    }
}
