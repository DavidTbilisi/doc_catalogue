<?php

namespace Database\Factories;

use App\Models\Io_types_translation;
use Illuminate\Database\Eloquent\Factories\Factory;

class Io_types_translationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Io_types_translation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'io_type_id' => 1, 
            'fields' => '{"string":"სტრიქონი"}'
        ];
    }
}
