<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('groups')->insert([
            'name' => "admin",
            'alias' => "Admin",
            'description' => "The administrator of the website",
        ]);
        DB::table('groups')->insert([
            'name' => "editor",
            'alias' => "Editor",
            'description' => "The editor of the website",
        ]);
        DB::table('groups')->insert([
            'name' => "user",
            'alias' => "User",
            'description' => "The user of the website",
        ]);

    }
}
