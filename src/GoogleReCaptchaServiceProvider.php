<?php

namespace AdamTorok96\GoogleReCaptcha;

use Illuminate\Support\Facades\Blade;
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

        Blade::directive('recaptchaDom', function () {
            /**
             * @var $captcha GoogleReCaptcha
             */
            $captcha = $this->app->make(GoogleReCaptcha::class);

            return '<?php echo \'' . $captcha->getCaptchaDom() . '\'; ?>';
        });

        Blade::directive('recaptchaJs', function () {
            /**
             * @var $captcha GoogleReCaptcha
             */
            $captcha = $this->app->make(GoogleReCaptcha::class);

            return '<?php echo \'' . $captcha->getJsDom() . '\'; ?>';
        });
    }

    public function provides()
    {
        return [GoogleReCaptcha::class];
    }
}