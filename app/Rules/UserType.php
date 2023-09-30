<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class UserType implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $request = request();
        $params = $request->all();

        $table = $params['user_type'];
        $field = $table.'_id';
        $value = $params['user_id'];

        if (in_array($table, ['admin', 'merchant', 'driver', 'client'])) {
            $exists = DB::table('mt_'.$table)->where($field, $value)->exists();
            if (!$exists) {
                $fail("The $table did not exists.");
            }
        }
    }
}
