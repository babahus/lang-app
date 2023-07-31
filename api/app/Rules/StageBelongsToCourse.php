<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Stage;

class StageBelongsToCourse implements Rule
{
    private $courseId;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($courseId)
    {
        $this->courseId = $courseId;
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
        $stage = Stage::where('id', $value)->where('course_id', $this->courseId)->first();
        return $stage !== null;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The selected stage does not belong to the specified course.';
    }
}
