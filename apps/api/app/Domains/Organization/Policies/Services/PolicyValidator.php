<?php

namespace App\Domains\Organization\Policies\Services;

use App\Models\OrganizationPolicy;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PolicyValidator
{
    public function validate(string $type, array $rules): void
    {
        $validationRules = match ($type) {
            'working_days' => ['required', 'array', 'min:1'],
            'business_hours' => ['required', 'array'],
            default => ['sometimes', 'array'],
        };

        $validator = Validator::make(['rules' => $rules], [
            'rules' => $validationRules,
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
