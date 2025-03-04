<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Journal;

class UpdateJournalRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('update', $this->journal);
    }

    public function rules()
    {
        return [
            'action' => 'required|string',
            'user_id' => 'nullable|exists:users,id',
        ];
    }
}
