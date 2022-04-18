<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Perms extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'permissionscustom';
    }
}
