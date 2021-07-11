<?php

namespace Database\Factories;

use App\Models\ScholarshipRequirementItemOption;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScholarshipRequirementItemOptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ScholarshipRequirementItemOption::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'item_id' => 1,
            'option' => $this->faker->sentence($nbWords = 4, $variableNbWords = true),
        ];
    }
}
