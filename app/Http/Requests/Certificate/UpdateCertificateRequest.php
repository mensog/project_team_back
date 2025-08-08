<?php

namespace App\Http\Requests\Certificate;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCertificateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file' => 'sometimes|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'event_id' => 'sometimes|nullable|exists:events,id',
            'title' => 'sometimes|required_without:issued_by|string|max:255',
            'issued_by' => 'sometimes|required_without:title|string|max:255',
            'issue_date' => 'sometimes|date|before_or_equal:today',
        ];
    }

    public function messages(): array
    {
        return [
            'event_id.exists' => 'Событие с ID :input не найдено.',
            'file.mimes' => 'Файл должен быть в формате: pdf, jpg, jpeg, png.',
            'file.max' => 'Файл не должен превышать 10 МБ.',
            'issue_date.before_or_equal' => 'Дата выдачи не может быть в будущем.',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('title') && !$this->has('issued_by')) {
            $this->merge(['issued_by' => $this->title]);
        }
    }
}
