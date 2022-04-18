<?php

namespace App\Providers;

use App\Perms\CustomPermission;
use Illuminate\Support\ServiceProvider;

class CustomPermissionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind("permissionscustom", function () {
            return new CustomPermission();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
