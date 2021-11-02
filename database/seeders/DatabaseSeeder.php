<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            GroupSeeder::class,
            UserSeeder::class,
            PermissionSeeder::class,
            AuthroutesSeeder::class,

            PermissionUserSeeder::class,
            GroupPermissionSeeder::class,
//            UserAuthSeeder::class,
//            GroutAuthSeeder::class,
        ]);
    }
}
