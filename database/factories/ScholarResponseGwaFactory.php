<?php

namespace Database\Factories;

use App\Models\ScholarResponseGwa;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScholarResponseGwaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ScholarResponseGwa::class;

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
            'gwa' => rand(100,250)*0.01,
        ];
    }
}
