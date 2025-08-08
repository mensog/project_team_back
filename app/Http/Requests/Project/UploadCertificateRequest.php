<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class UploadCertificateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'certificate' => ['required', 'file', 'mimes:pdf,jpeg,png', 'max:10240'],
        ];
    }
}
