<?php

namespace AdamTorok96\GoogleReCaptcha;

use Illuminate\Support\ServiceProvider;

class GoogleReCaptchaServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/Config/config.php', 'captcha'
        );

        $this->app->singleton(GoogleReCaptcha::class, function ($app) {
            return new GoogleReCaptcha($app['config']['captcha']);
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/Config/config.php' => config_path('captcha.php'),
        ]);
    }

    public function provides()
    {
        return [GoogleReCaptcha::class];
    }
}