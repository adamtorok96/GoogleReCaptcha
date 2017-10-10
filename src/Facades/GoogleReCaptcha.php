<?php

namespace AdamTorok96\GoogleReCaptcha\Facades;

use Illuminate\Support\Facades\Facade;

class GoogleReCaptcha extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \AdamTorok96\GoogleReCaptcha\GoogleReCaptcha::class;
    }
}