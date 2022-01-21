<?php

namespace Database\Seeders;

use App\Models\Io_types_translation;
use Illuminate\Database\Seeder;

class HumanreadableSeeder extends Seeder
{
    public function run()
    {
        $itt = new Io_types_translation();
        $itt->io_type_id = 1;
        $itt->fields = '{"name":"სახელი","reference":"რეფერენსი"}';
        $itt->save();
    }
}
