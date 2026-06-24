<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $systemHealth = [
            'status' => 'Healthy',
            'db' => 'Connected',
            'env' => config('app.env'),
        ];

        try {
            DB::connection()->getPdo();
        } catch (\Throwable $e) {
            $systemHealth = [
                'status' => 'Unhealthy',
                'db' => 'Down',
                'env' => config('app.env'),
            ];
        }

        View::share('systemHealth', $systemHealth);
    }
}
