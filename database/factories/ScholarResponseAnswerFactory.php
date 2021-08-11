<?php

namespace Database\Factories;

use App\Models\ScholarResponseAnswer;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScholarResponseAnswerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ScholarResponseAnswer::class;

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
            'answer' => $this->faker->text($maxNbChars = 100)
        ];
    }
}
