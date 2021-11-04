<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('group_permission')->insert([
            'group_id' => 1,
            'permission_id' => 1,
        ]);

        DB::table('group_permission')->insert([
            'group_id' => 1,
            'permission_id' => 2,
        ]);

        DB::table('group_permission')->insert([
            'group_id' => 1,
            'permission_id' => 3,
        ]);

        DB::table('group_permission')->insert([
            'group_id' => 2,
            'permission_id' => 1,
        ]);

        DB::table('group_permission')->insert([
            'group_id' => 3,
            'permission_id' => 1,
        ]);

    }
}
