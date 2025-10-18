<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\PrescriptionRepositoryInterface;
use App\Repositories\EloquentPrescriptionRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PrescriptionRepositoryInterface::class, EloquentPrescriptionRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
