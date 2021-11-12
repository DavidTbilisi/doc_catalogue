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
        $level = random_int(1,3);

        return [
            'io_type_id' => $type_id,
            'suffix' => "",
            'identifier' => $uid,
            'prefix' => "",
            'reference' => "{$type_id}_{$uid}_{$parent_id}",
            'level' => $level,
            'parentid' => $parent_id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
