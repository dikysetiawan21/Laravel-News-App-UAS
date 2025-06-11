<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        //
    ];

    public function boot(): void
    {
        Log::info('AuthServiceProvider booted');
        Gate::define('admin', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('editor', function (User $user) {
            return $user->role === 'editor' || $user->role === 'admin';
        });

        Gate::define('wartawan', function (User $user) {
            return $user->role === 'wartawan' || $user->role === 'admin';
        });

        Gate::define('manage-users', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('approve-news', function (User $user) {
            Log::info('Gate approve-news', ['user_id' => $user->id, 'role' => $user->role]);
            return $user->role === 'editor' || $user->role === 'admin';
        });

        Gate::define('create-news', function (User $user) {
            return $user->role === 'wartawan' || $user->role === 'admin';
        });

        Gate::define('view-news', function (User $user) {
            return in_array($user->role, ['admin', 'editor', 'wartawan', 'user']);
        });
    }
}