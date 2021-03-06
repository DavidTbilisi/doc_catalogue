<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => "Administrator",
            'email' => "dchincharashvili@archive.gov.ge",
            'password' => Hash::make("123456789"),
            'group_id' => 1
        ]);

        DB::table('users')->insert([
            'name' => "Administrator",
            'email' => "admin@archive.gov.ge",
            'password' => Hash::make("123456789"),
            'group_id' => 1
        ]);

        DB::table('users')->insert([
            'name' => "Editor",
            'email' => "editor@archive.gov.ge",
            'password' => Hash::make("123456789"),
            'group_id' => 2
        ]);

        DB::table('users')->insert([
            'name' => "User",
            'email' => "user@archive.gov.ge",
            'password' => Hash::make("123456789"),
            'group_id' => 3
        ]);
    }
}
