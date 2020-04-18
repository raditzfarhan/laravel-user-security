<?php

namespace RaditzFarhan\UserSecurity;

use Illuminate\Support\ServiceProvider;

class UserSecurityServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/rfauthenticator.php' => $this->configPath('rfauthenticator.php'),
        ]);

        $this->loadMigrationsFrom(__DIR__.'/migrations');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/rfauthenticator.php',
            'rfauthenticator'
        );

        // Register the service the package provides.
        $this->app->singleton('RFAuthenticator', function ($app) {
            return new RFAuthenticator;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['RFAuthenticator'];
    }

    public function configPath($file)
    {
        if (function_exists('config_path')) {
            return config_path($file);
        } else {
            return base_path($file);
        }
    }
}
