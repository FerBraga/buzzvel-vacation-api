<?php

namespace App\Providers;

use App\Repositories\Interfaces\VacationRepositoryInterface;
use App\Repositories\VacationRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(VacationRepositoryInterface::class, VacationRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
