<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCitizenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'nationality' => ['required', 'string', 'max:255'],
            'passport_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('citizens', 'passport_number')->ignore($this->route('citizen')),
            ],
            'phone' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'address_in_ghana' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:255'],
            'region' => ['nullable', 'string', 'max:255'],
            'registration_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
