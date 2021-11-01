<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroutAuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('group_authroutes')->insert([
            'group_id'=>1,
            'authroutes_id'=>2
        ]);
        DB::table('group_authroutes')->insert([
            'group_id'=>1,
            'authroutes_id'=>1
        ]);
        DB::table('group_authroutes')->insert([
            'group_id'=>1,
            'authroutes_id'=>7
        ]);
    }
}
