<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Pulse\Facades\Pulse;

class PulseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        Pulse::user(fn($user) => [
            'name' => $user->name,
            'extra' => $user->email,
            'avatar' => $user->profile_photo_url,
        ]);

        Gate::define('viewPulse', function (User $user) {
            return $user->isAdmin();
        });
    }
}
