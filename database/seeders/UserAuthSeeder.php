<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserAuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_authroutes')->insert([
            'user_id'=>1,
            'authroutes_id'=>2
        ]);
        DB::table('user_authroutes')->insert([
            'user_id'=>1,
            'authroutes_id'=>1
        ]);
        DB::table('user_authroutes')->insert([
            'user_id'=>1,
            'authroutes_id'=>7
        ]);
    }
}
