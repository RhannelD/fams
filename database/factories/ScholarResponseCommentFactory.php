<?php

namespace Database\Factories;

use App\Models\ScholarResponseComment;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScholarResponseCommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ScholarResponseComment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'response_id' => 1,
            'user_id' => 1,
            'comment' => $this->faker->text($maxNbChars = 100)
        ];
    }
}
