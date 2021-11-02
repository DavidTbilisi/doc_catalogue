<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            'name' => "Read",
            'description' => "Can do anything",
            'power' => 1,
        ]);
        DB::table('permissions')->insert([
            'name' => "Change",
            'description' => "Can do something",
            'power' => 2,
        ]);
        DB::table('permissions')->insert([
            'name' => "Something else",
            'description' => "Can do nothing",
            'power' => 0,
        ]);


    }
}
