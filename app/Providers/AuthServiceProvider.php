<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Gikuha nato ang Dusterio/Passport logic diri kay JWT na atong gamit.
        // Ang JWT-Auth Provider na ang bahala sa authentication logic.
        
        $this->app['auth']->viaRequest('api', function ($request) {
            // Pasagdan lang ni nato nga empty kay ang JWT guard sa config/auth.php
            // na ang mo-handle sa pag-validate sa token.
        });
    }
}