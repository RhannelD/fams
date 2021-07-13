<?php

namespace Database\Factories;

use App\Models\ScholarshipRequirementItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class ScholarshipRequirementItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ScholarshipRequirementItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $types = ['question', 'question', 'file', 'file', 'file', 'radio', 'check'];

        return [
            'requirement_id' => 1,
            'item' => $this->faker->sentence($nbWords = 4, $variableNbWords = true),
            'type' => Arr::random($types),
            'note' => $this->faker->sentence($nbWords = 10, $variableNbWords = true),
            'position' => 1,
        ];
    }
}
