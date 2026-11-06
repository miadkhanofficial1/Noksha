<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NotDisposableEmail implements ValidationRule
{
    /**
     * Common disposable / temporary email domains blocked on Noksha.
     */
    protected array $disposableDomains = [
        'tempmail.com',
        '10minutemail.com',
        'guerrillamail.com',
        'mailinator.com',
        'yopmail.com',
        'trashmail.com',
        'sharklasers.com',
        'dispostable.com',
    ];

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $email = strtolower(trim((string) $value));
        $domain = substr(strrchr($email, "@"), 1);

        if ($domain && in_array($domain, $this->disposableDomains, true)) {
            $fail('Temporary or disposable email domains are not allowed on Noksha.');
        }
    }
}
