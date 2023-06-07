<?php

namespace App\Providers;

use App\Repositories\ITaskRepository;
use App\Repositories\ITaskStatRepository;
use App\Repositories\TaskRepository;
use App\Repositories\TaskStatRepository;
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
        $this->app->bind(ITaskStatRepository::class, function(){
            return new TaskStatRepository;
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
