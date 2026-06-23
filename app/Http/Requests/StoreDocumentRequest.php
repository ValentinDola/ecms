<?php

namespace App\Http\Requests;

use App\Models\AssistanceCase;
use App\Models\Citizen;
use App\Models\Document;
use App\Models\Visa;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'documentable_type' => ['required', 'string', Rule::in([
                Citizen::class,
                Visa::class,
                AssistanceCase::class,
            ])],
            'documentable_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', Rule::in(array_keys(Document::CATEGORIES))],
            'file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $type = $this->input('documentable_type');
            $id = $this->input('documentable_id');

            $exists = match ($type) {
                Citizen::class => Citizen::whereKey($id)->exists(),
                Visa::class => Visa::whereKey($id)->exists(),
                AssistanceCase::class => AssistanceCase::whereKey($id)->exists(),
                default => false,
            };

            if (! $exists) {
                $validator->errors()->add('documentable_id', 'The selected record does not exist.');
            }
        });
    }
}
