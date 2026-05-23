<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Philippine mobile number validator.
 *
 * Rules:
 *  - Exactly 11 digits
 *  - Must start with "09"
 *  - Digits only (no spaces, dashes, letters, or special characters)
 *
 * Examples of valid input:  09123456789, 09171234567
 * Examples of invalid:      9123456789, 091-234-567, 09abc123456, +639171234567
 */
class PhilippineMobileNumber implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Null/empty values pass — combine this with "required" if you need a value
        if ($value === null || $value === '') {
            return;
        }

        $value = (string) $value;

        // Reject non-digit characters
        if (!ctype_digit($value)) {
            $fail('The :attribute must contain digits only. No spaces, dashes, or letters allowed.');
            return;
        }

        // Must be exactly 11 digits
        if (strlen($value) !== 11) {
            $fail('The :attribute must be exactly 11 digits.');
            return;
        }

        // Must start with 09
        if (!str_starts_with($value, '09')) {
            $fail('The :attribute must start with "09" (e.g., 09123456789).');
            return;
        }
    }
}
