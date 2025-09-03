<?php

namespace App\Http\Requests\Notifications;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Uuid;

class NotificationReadRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'uuid' => ['required|uuid'],
        ];
    }
}
