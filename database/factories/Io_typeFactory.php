<?php

namespace Database\Factories;

use App\Models\Io_type;
use Illuminate\Database\Eloquent\Factories\Factory;

class Io_typeFactory extends Factory
{
    protected $model = Io_type::class;

    public function definition()
    {
        $name = $this->faker->firstName();
        
        return [
            "name"=>$name,
            "table"=>strtolower($name),
        ];
    }
}
