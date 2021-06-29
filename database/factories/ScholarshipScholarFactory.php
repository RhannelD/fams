<?php

namespace Database\Factories;

use App\Models\ScholarshipScholar;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScholarshipScholarFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ScholarshipScholar::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'       => 1,
            'category_id'   => 1,
        ];
    }
}
