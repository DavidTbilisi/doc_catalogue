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
        $permission_count = range(1,7);
        $groups = [
            "Admin" => 1,
            'Editor' => 2,
            'User' => 3,
            ];
        foreach($permission_count as $permission_id):
            DB::table("group_permission")->insert([
                'permission_id' => $permission_id,
                'group_id' => $groups['Admin'],
            ]);
        endforeach;

        foreach([1,2,3,5,6] as $permission_id):
            DB::table("group_permission")->insert([
                'permission_id' => $permission_id,
                'group_id' => $groups['Editor'],
            ]);
        endforeach;


        foreach([2,5] as $permission_id):
            DB::table("group_permission")->insert([
                'permission_id' => $permission_id,
                'group_id' => $groups['User'],
            ]);
        endforeach;

    }
}
