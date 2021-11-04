<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class AuthroutesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $allRoutes = Route::getRoutes()->get();

        foreach ($allRoutes as $route){
            if (!str_contains($route->uri(), 'debugbar') && !str_contains($route->uri(), 'ignition')) {
                DB::table('authroutes')->insert([
                    'url' => $route->uri(),
                    'method'=> $route->methods()[0],
                    'user' => 1,
                    'group' => 1,
                    'other' => 1,
                ]);
            }
        }
    }
}
