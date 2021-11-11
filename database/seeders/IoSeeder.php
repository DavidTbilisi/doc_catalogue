<?php

namespace Database\Seeders;

use App\Models\Io;
use Illuminate\Database\Seeder;

class IoSeeder extends Seeder
{

    public function run()
    {
        Io::factory()->count(90)->create();
    }
}
