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
        $names = ["fonds"=>"ფონდი", "series"=>"ანაწერი", "files"=>"საქმე","items"=>"დოკუმენტები" ];
        foreach($names as $tablename => $name):
        DB::table('io_types')->insert([
            'name' => $name,
            'table' => $tablename,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        endforeach;
    }
}
