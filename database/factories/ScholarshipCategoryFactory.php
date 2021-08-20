<?php

namespace Database\Factories;

use App\Models\ScholarshipCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScholarshipCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ScholarshipCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $amount = rand(1,20);

        return [
            'scholarship_id'=> 1,
            'category'   => $this->faker->unique()->company,
            'amount'   => ($amount * 100),
        ];
    }
}
