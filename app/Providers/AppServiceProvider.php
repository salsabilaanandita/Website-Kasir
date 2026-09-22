<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Sanitize database options if parsed as string from DATABASE_URL
        $options = config('database.connections.pgsql.options');
        if (!is_array($options)) {
            config(['database.connections.pgsql.options' => []]);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
