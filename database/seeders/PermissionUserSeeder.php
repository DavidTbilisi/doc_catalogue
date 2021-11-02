<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("permission_user")->insert([
            'permission_id' => 1,
            'user_id' => 1,
            'authroutes_id' => 4,
        ]);

        DB::table("permission_user")->insert([
            'permission_id' => 2,
            'user_id' => 1,
            'authroutes_id' => 5,
        ]);

        DB::table("permission_user")->insert([
            'permission_id' => 3,
            'user_id' => 1,
            'authroutes_id' => 6,
        ]);

        DB::table("permission_user")->insert([
            'permission_id' => 3,
            'user_id' => 1,
            'authroutes_id' => 7,
        ]);
    }
}
