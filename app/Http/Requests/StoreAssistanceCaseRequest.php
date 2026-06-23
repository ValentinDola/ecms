<?php

namespace App\Http\Requests;

use App\Models\AssistanceCase;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAssistanceCaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'citizen_id' => ['required', 'exists:citizens,id'],
            'case_type' => ['required', 'string', Rule::in(array_keys(AssistanceCase::TYPES))],
            'status' => ['required', 'string', Rule::in(array_keys(AssistanceCase::STATUSES))],
            'opened_at' => ['required', 'date'],
            'description' => ['required', 'string'],
            'actions_taken' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
