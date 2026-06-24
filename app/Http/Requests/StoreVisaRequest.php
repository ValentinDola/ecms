<?php

namespace App\Http\Requests;

use App\Models\Visa;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVisaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return $this->rulesForVisa();
    }

    protected function rulesForVisa(): array
    {
        return [
            'citizen_id' => ['nullable', 'exists:citizens,id'],
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
