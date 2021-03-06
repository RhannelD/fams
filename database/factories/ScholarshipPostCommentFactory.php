<?php

namespace Database\Factories;

use App\Models\ScholarshipPostComment;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScholarshipPostCommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ScholarshipPostComment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'post_id' => 1,
            'user_id' => 1,
            'comment' => $this->faker->text($maxNbChars = 100)
        ];
    }
}
