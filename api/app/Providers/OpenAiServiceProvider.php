<?php

namespace App\Providers;

use App\Services\OpenAiService;
use Illuminate\Support\ServiceProvider;

class OpenAiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(OpenAiService::class, function ($app) {
            return OpenAiService::getInstance();
        });
    }


    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
