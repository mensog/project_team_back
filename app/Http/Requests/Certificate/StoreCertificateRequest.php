<?php

namespace App\Http\Requests\Certificate;

use Illuminate\Foundation\Http\FormRequest;

class StoreCertificateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB
            'event_id' => 'nullable|exists:events,id',
            'issued_by' => 'required|string|max:255',
            'issue_date' => 'required|date|before_or_equal:today',
        ];
    }
}
