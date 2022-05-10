<?php

namespace Database\Factories;

use App\Models\Io;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class IoFactory extends Factory
{

    protected $model = Io::class;

    public function definition()
    {
        $uid = $this->faker->buildingNumber();
        $type_id = random_int(1,3);
        $parent_id = random_int(1,3);


        return [
            'io_type_id' => 1,
            'suffix' => "",
            'identifier' => $uid,
            'prefix' => "",
            'reference' => "GE_{$type_id}_{$uid}_{$parent_id}",
            "data_id"=>1,
            'level' => $type_id,
            'permission' => 17,
            'parent_id' => $parent_id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
