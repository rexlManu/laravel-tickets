<?php

namespace RexlManu\LaravelTickets;

use Illuminate\Support\ServiceProvider;
use RexlManu\LaravelTickets\Commands\AutoCloseCommand;

class LaravelTicketsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
//        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'laravel-tickets');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-tickets');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('laravel-tickets.php'),
            ], 'config');

            // Publishing the views.
            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/laravel-tickets'),
            ], 'views');

            $this->publishes([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], 'migrations');

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/laravel-tickets'),
            ], 'assets');*/

            // Publishing the translation files.
//            $this->publishes([
//                __DIR__ . '/../resources/lang' => resource_path('lang/vendor/laravel-tickets'),
//            ], 'lang');

            // Registering package commands.
            $this->commands([ AutoCloseCommand::class ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'laravel-tickets');

        // Register the main class to use with the facade
        $this->app->singleton('laravel-tickets', function () {
            return new LaravelTickets;
        });
    }
}
