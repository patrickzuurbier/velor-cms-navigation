<?php

declare(strict_types=1);

namespace Velor\Navigation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NavigationUrl implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || ! $this->isValid($value)) {
            $fail('velor-navigation::validation.navigation_url')->translate();
        }
    }

    protected function isValid(string $value): bool
    {
        if ($value === '' || preg_match('/[\x00-\x20\x7F<>"{}|\\\\^`]/', $value) === 1) {
            return false;
        }

        if (str_starts_with($value, '#')) {
            return preg_match("/^#[A-Za-z0-9][A-Za-z0-9._~!$&'()*+,;=:@%\/-]*$/", $value) === 1;
        }

        if (str_starts_with($value, '/')) {
            return ! str_starts_with($value, '//');
        }

        $scheme = strtolower((string) parse_url($value, PHP_URL_SCHEME));

        return match ($scheme) {
            'http', 'https' => filter_var($value, FILTER_VALIDATE_URL) !== false,
            'mailto'        => filter_var(substr($value, 7), FILTER_VALIDATE_EMAIL) !== false,
            'tel'           => preg_match('/^\+?[0-9][0-9().-]*$/', substr($value, 4)) === 1,
            default         => false,
        };
    }
}
