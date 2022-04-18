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
            'name' => "View Object",
            'description' => "Can view object",
            'power' => 1,
        ]);

        DB::table('permissions')->insert([
            'name' => "Edit Object",
            'description' => "Can edit object",
            'power' => 2,
        ]);

        DB::table('permissions')->insert([
            'name' => "Delete Object",
            'description' => "Can delete object",
            'power' => 4,
        ]);
        DB::table('permissions')->insert([
            'name' => "View Document",
            'description' => "Can view document",
            'power' => 8,
        ]);

        DB::table('permissions')->insert([
            'name' => "Edit Document",
            'description' => "Can edit document",
            'power' => 16,
        ]);

        DB::table('permissions')->insert([
            'name' => "Delete Document",
            'description' => "Can delete document",
            'power' => 32,
        ]);


    }
}
