<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

//      *************************************************
        Blade::directive('hasPerm', function ($expression) {
            return "<?php if ( Perms::hasPerm({$expression}) )  : ?>";
        });

        Blade::directive('hasNot', function () {
            return '<?php else: ?>';
        });

        Blade::directive('hasPermEnd', function () {
            return '<?php endif; ?>';
        });



//      *************************************************
        Blade::directive('hasPerms', function ($expression) {
            return "<?php if ( Perms::hasPerms({$expression}) )  : ?>";
        });

        Blade::directive('hasNot', function () {
            return '<?php else: ?>';
        });

        Blade::directive('hasPermsEnd', function () {
            return '<?php endif; ?>';
        });



//      *************************************************
        Blade::directive('isGroup', function ($expression) {
            return "<?php if ( Perms::isGroup({$expression}) )  : ?>";
        });

        Blade::directive('notGroup', function () {
            return '<?php else: ?>';
        });

        Blade::directive('isGroupEnd', function () {
            return '<?php endif; ?>';
        });
    }
}
