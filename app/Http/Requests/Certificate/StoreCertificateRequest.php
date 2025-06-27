<?php

namespace App\Http\Requests\Certificate;

use Illuminate\Foundation\Http\FormRequest;

class StoreCertificateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB
            'event_id' => 'nullable|exists:events,id',
            'title' => 'required_without:issued_by|string|max:255', // Псевдоним для issued_by
            'issued_by' => 'required_without:title|string|max:255', // Для обратной совместимости
            'issue_date' => 'required|date|before_or_equal:today',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('title') && !$this->has('issued_by')) {
            $this->merge(['issued_by' => $this->title]);
        }
    }
}
