<?php

namespace Database\Factories;

use App\Models\ScholarResponseUnit;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScholarResponseUnitFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ScholarResponseUnit::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'response_id' => 1,
            'item_id' => 1,
            'units' => rand(18,23),
        ];
    }
}
