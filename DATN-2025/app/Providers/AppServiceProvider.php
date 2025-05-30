<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use App\Http\ViewComposers\CartComposer;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         View::composer('layout2', CartComposer::class);
    }
}
