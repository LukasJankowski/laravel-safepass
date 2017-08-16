<?php

namespace LukasJankowski\SafePass;

use Illuminate\Support\ServiceProvider;

class SafePassServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        validator()->extend(
            'safepass',
            'LukasJankowski\SafePass\PasswordStrengthMeter@validate',
            'The password you entered is easily guessable. Please use a more complex one.'
        );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        app()->bind(PasswordStrengthMeter::class);
    }
}
