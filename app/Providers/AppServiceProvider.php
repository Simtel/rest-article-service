<?php

namespace App\Providers;

use App\Contracts\ArticleRepositoryInterface;
use App\Repositories\ArticleRepository;
use App\Services\Validation\ArticleValidationService;
use App\Services\Validation\TagValidationService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    #[\Override]
    public function register(): void
    {
        $this->app->bind(ArticleRepositoryInterface::class, ArticleRepository::class);

        // Register validation services
        $this->app->singleton(ArticleValidationService::class);
        $this->app->singleton(TagValidationService::class);
    }
}
