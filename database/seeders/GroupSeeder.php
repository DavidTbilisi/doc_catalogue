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
            'name' => "Admin",
            'description' => "The administrator of the website",
        ]);
        DB::table('groups')->insert([
            'name' => "Editor",
            'description' => "The editor of the website",
        ]);
        DB::table('groups')->insert([
            'name' => "User",
            'description' => "The user of the website",
        ]);

    }
}
