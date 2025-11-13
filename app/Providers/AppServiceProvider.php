<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\PrescriptionRepositoryInterface;
use App\Repositories\EloquentPrescriptionRepository;
use App\Services\Payments\PaymentProviderInterface;
use App\Services\Payments\PaymongoCheckoutProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PrescriptionRepositoryInterface::class, EloquentPrescriptionRepository::class);
        $this->app->bind(PaymentProviderInterface::class, PaymongoCheckoutProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
