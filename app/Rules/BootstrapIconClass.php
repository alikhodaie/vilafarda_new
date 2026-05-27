<?php

namespace App\Rules;

use App\Support\BootstrapIconRegistry;
use Illuminate\Contracts\Validation\Rule;

class BootstrapIconClass implements Rule
{
    public function passes($attribute, $value): bool
    {
        if (! is_string($value) || $value === '') {
            return false;
        }

        return BootstrapIconRegistry::isAllowed($value);
    }

    public function message(): string
    {
        return __('validation.bootstrap_icon');
    }
}
