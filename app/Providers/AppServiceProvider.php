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

        // 🛑 FIX FOR VERCEL ASSET PATHS 🛑
        // This ensures the asset() and @vite() helpers correctly resolve the public folder
        // when deployed to Vercel's serverless environment.
        if (isset($_SERVER['SERVER_NAME'])) {
            $this->app->bind('path.public', function () {
                return base_path('public');
            });
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