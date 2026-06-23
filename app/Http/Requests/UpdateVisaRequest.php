<?php

namespace App\Http\Requests;

use App\Models\Visa;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVisaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'citizen_id' => ['nullable', 'exists:citizens,id'],
            'visa_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('visas', 'visa_number')->ignore($this->route('visa')),
            ],
            'passport_number' => ['required', 'string', 'max:255'],
            'applicant_first_name' => ['required', 'string', 'max:255'],
            'applicant_last_name' => ['required', 'string', 'max:255'],
            'visa_type' => ['required', 'string', Rule::in(array_keys(Visa::TYPES))],
            'issue_date' => ['required', 'date'],
            'expiry_date' => ['required', 'date', 'after_or_equal:issue_date'],
            'status' => ['required', 'string', Rule::in(array_keys(Visa::STATUSES))],
            'purpose_of_visit' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
