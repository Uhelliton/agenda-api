<?php

namespace App\Support\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidDaysRule implements ValidationRule
{
    /**
     * Rules to day week valid
     * only: seg|ter|qua|qui|sex
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $dayWeek = (int) Carbon::create($value)->format('w');

        if(!($dayWeek >= 1 && $dayWeek <= 5)){
            $fail('The :attribute is not day week valid');
        }
    }
}
