<?php

namespace BlackCup\Invites;

use Illuminate\Support\ServiceProvider;
use BlackCup\Invites\Commands\MakeInviteCommand;

class InvitesServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'invites');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'invites');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Publish routes if they are enabled
        if (config('invites.routes', true)) {
            app('invites')->routes();
        }

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/invites.php', 'invites');

        // Register the service the package provides.
        $this->app->singleton('invites', function ($app) {
            return new Invites;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['invites'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/invites.php' => config_path('invites.php'),
        ], ['invites-config', 'config']);

        // Publishing the views.
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/invites'),
        ], ['invites-views', 'views']);

        // Publishing the translation files.
        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/invites'),
        ], ['invites-lang', 'lang']);

        // Registering package commands.
        $this->commands([
            MakeInviteCommand::class,
        ]);
    }
}
