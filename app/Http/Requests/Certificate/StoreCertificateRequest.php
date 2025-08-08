<?php

namespace App\Http\Requests\Certificate;

use Illuminate\Foundation\Http\FormRequest;

class StoreCertificateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'event_id' => 'nullable|exists:events,id',
            'title' => 'required_without:issued_by|string|max:255',
            'issued_by' => 'required_without:title|string|max:255',
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
