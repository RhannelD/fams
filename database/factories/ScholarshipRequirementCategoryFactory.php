<?php

namespace Database\Factories;

use App\Models\ScholarshipRequirementCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScholarshipRequirementCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ScholarshipRequirementCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'requirement_id' => 1,
            'category_id' => 1,
        ];
    }
}
