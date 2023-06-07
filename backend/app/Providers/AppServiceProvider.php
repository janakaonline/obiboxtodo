<?php

namespace App\Providers;

use App\Repositories\ITaskRepository;
use App\Repositories\TaskRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ITaskRepository::class, function(){
            return new TaskRepository;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
