<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class NotScholarOfScholarshipRule implements Rule
{
    public $scholarship_id;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($scholarship_id)
    {
        $this->scholarship_id = $scholarship_id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return !User::where('email', $value)
            ->whereScholarOf($this->scholarship_id)
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute is already a scholar of this Scholarship Program.';
    }
}
