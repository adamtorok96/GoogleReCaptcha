<?php

return [
    /**
     * Use this in the HTML code your site serves to users.
     */
    'site_key'      => env('GOOGLE_RECAPTCHA_SITE_KEY'),

    /**
     * Use this for communication between your site and Google. Be sure to keep it a secret.
     */
    'secret_key'    => env('GOOGLE_RECAPTCHA_SECRET_KEY')
];