<?php

namespace App\Http\Requests\Domain;

use App\Models\Domain;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateDomainRequest extends StoreDomainRequest
{
    /**
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        /** @var Domain|null $domain */
        $domain = $this->route('domain');

        return [
            'domain' => [
                'required',
                'string',
                'lowercase',
                'max:255',
                'regex:/^(?!-)(?:[a-z0-9-]{1,63}\.)+[a-z]{2,63}$/i',
                Rule::unique('domains', 'domain')
                    ->where(fn ($query) => $query->where('user_id', $this->user()->id))
                    ->ignore($domain?->id),
            ],
            'check_interval' => ['required', 'integer', 'min:30', 'max:86400'],
            'timeout' => ['required', 'integer', 'min:1', 'max:60'],
            'method' => ['required', Rule::in(['GET', 'HEAD'])],
        ];
    }
}
