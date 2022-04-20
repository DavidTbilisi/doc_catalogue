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

        foreach(range(1,7) as $i):
            DB::table("permission_user")->insert([
                'permission_id' => $i,
                'user_id' => 1,
            ]);
        endforeach;


    }
}
