<?php

namespace App\Providers;

use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(WhatsAppService::class);
    }

    public function boot(): void
    {
        // Admins implicitly have every permission.
        Gate::before(function ($user, $ability) {
            return $user->hasRole('admin') ? true : null;
        });
    }
}
