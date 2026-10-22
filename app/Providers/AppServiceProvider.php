<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (config('debugbar.enabled')) {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }

        if ($this->shouldRegisterIdeHelper()) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }

    /**
     * Determine whether the IDE helper should be registered.
     *
     * The IDE helper is a development-only tool that can regenerate helper
     * files. It must never be active on the production server, so it is
     * gated on an explicit flag rather than on the application environment.
     *
     * @return bool
     */
    protected function shouldRegisterIdeHelper(): bool
    {
        return (bool) env('IDE_HELPER_ENABLED', false)
            && env('APP_ENV', 'production') !== 'production';
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
