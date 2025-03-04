<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Journal;

class StoreJournalRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('create', Journal::class);
    }

    public function rules()
    {
        return [
            'action' => 'required|string',
            'user_id' => 'nullable|exists:users,id',
        ];
    }
}
