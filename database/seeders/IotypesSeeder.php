<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IotypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(["ფონდი", "ანაწერი", "საქმე", ] as $name):
        DB::table('io_types')->insert([
            'name' => $name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        endforeach;
    }
}
