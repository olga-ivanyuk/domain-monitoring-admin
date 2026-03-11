<?php

namespace App\Http\Requests\Domain;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDomainRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'domain' => [
                'required',
                'string',
                'lowercase',
                'max:255',
                'regex:/^(?!-)(?:[a-z0-9-]{1,63}\.)+[a-z]{2,63}$/i',
                Rule::unique('domains', 'domain')
                    ->where(fn ($query) => $query->where('user_id', $this->user()->id)),
            ],
            'check_interval' => ['required', 'integer', 'min:30', 'max:86400'],
            'timeout' => ['required', 'integer', 'min:1', 'max:60'],
            'method' => ['required', Rule::in(['GET', 'HEAD'])],
        ];
    }
}
