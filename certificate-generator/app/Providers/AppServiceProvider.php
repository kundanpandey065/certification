<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Repositories\BulkExportRepositoryInterface;
use App\Repositories\BulkExportRepository;
use App\Repositories\CertificateRepositoryInterface;
use App\Repositories\CertificateRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            CertificateRepositoryInterface::class,
            CertificateRepository::class
        );

        $this->app->bind(
            BulkExportRepositoryInterface::class,
            BulkExportRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
    }
}
