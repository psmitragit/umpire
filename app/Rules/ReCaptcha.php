<?php

namespace App\Rules;

use Closure;
use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Validation\ValidationRule;

class ReCaptcha implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $response = Http::get("https://www.google.com/recaptcha/api/siteverify",[

            'secret' => env('CAPTCHA_SECRET_KEY'),

            'response' => $value

        ]);


        if (!($response->json()["success"] ?? false)) {

              $fail('The google recaptcha is required.');

        }
    }
}
