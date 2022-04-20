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

        // OBJECTS
        DB::table('permissions')->insert([
            'name' => "Add Object",
            'const_name' => "addObject",
            'description' => "Can add document",
            'power' => 1,
        ]);

        DB::table('permissions')->insert([
            'name' => "View Object",
            'const_name' => "viewObject",
            'description' => "Can view object",
            'power' => 1,
        ]);

        DB::table('permissions')->insert([
            'name' => "Edit Object",
            'const_name' => "editObject",
            'description' => "Can edit object",
            'power' => 1,
        ]);

        DB::table('permissions')->insert([
            'name' => "Delete Object",
            'const_name' => "deleteObject",
            'description' => "Can delete object",
            'power' => 1,
        ]);



        // DOCUMENTS

        DB::table('permissions')->insert([
            'name' => "View Document",
            'const_name' => "viewDocument",
            'description' => "Can view document",
            'power' => 1,
        ]);

        DB::table('permissions')->insert([
            'name' => "Add Document",
            'const_name' => "addDocument",
            'description' => "Can add document",
            'power' => 1,
        ]);

        DB::table('permissions')->insert([
            'name' => "Delete Document",
            'const_name' => "deleteDocument",
            'description' => "Can delete document",
            'power' => 1,
        ]);







    }
}
