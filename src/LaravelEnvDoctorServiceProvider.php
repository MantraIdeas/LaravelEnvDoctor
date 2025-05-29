<?php

namespace mantraideas\LaravelEnvDoctor;

use Illuminate\Support\ServiceProvider;
use Mantraideas\LaravelEnvDoctor\Commands\EnvDoctorCommand;

class LaravelEnvDoctorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->commands([
            EnvDoctorCommand::class,
        ]);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/config/laravel-env-doctor.php' => config_path('laravel-env-doctor.php'),
        ], 'laravel-env-doctor-config');
    }
}
