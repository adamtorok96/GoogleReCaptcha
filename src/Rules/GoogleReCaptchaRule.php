<?php


namespace AdamTorok96\GoogleReCaptcha\Rules;

use Illuminate\Contracts\Validation\Rule;


class GoogleReCaptchaRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        /**
         * @var $recaptcha \AdamTorok96\GoogleReCaptcha\GoogleReCaptcha
         */
        $reCaptcha = app(\AdamTorok96\GoogleReCaptcha\GoogleReCaptcha::class);

        return $reCaptcha->isValid($value, request()->ip());
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Hibás Google ReCaptcha!';
    }
}