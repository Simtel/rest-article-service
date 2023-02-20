<?php

namespace App\Providers;

use App\Contracts\ArticleRepositoryInterface;
use App\Repositories\ArticleRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(ArticleRepositoryInterface::class, ArticleRepository::class);
    }
}
