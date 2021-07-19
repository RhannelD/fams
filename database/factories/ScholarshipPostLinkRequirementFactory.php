<?php

namespace Database\Factories;

use App\Models\ScholarshipPostLinkRequirement;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScholarshipPostLinkRequirementFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ScholarshipPostLinkRequirement::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'post_id' => 1,
            'requirement_id' => 1,
        ];
    }
}
