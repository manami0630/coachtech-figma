<?php

namespace App\Providers;

public function boot()
{
    $this->configurePermissions();

    Jetstream::deleteUsersUsing(DeleteUser::class);

    // register new RegisterResponse
    $this->app->singleton(
        \Laravel\Fortify\Contracts\RegisterResponse::class,
        \App\Http\Responses\RegisterResponse::class
    );
}