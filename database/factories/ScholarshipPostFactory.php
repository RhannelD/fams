<?php

namespace Database\Factories;

use App\Models\ScholarshipPost;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScholarshipPostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ScholarshipPost::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = '';
        if (rand(0,5) != 0) {
            $title = $this->faker->sentence($nbWords = 6, $variableNbWords = true);
        }

        return [
            'user_id' => 2,
            'scholarship_id' => 1,
            'title' => $title,
            'post' => $this->faker->text($maxNbChars = 400),
            'promote' => false,
        ];
    }
}
