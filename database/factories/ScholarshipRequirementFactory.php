<?php

namespace Database\Factories;

use App\Models\ScholarshipRequirement;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class ScholarshipRequirementFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ScholarshipRequirement::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // $end = $this->faker->dateTimeThisYear($max = 'now', $timezone = null);
        $end = $this->faker->dateTimeBetween($startDate = '-16 weeks', $endDate = '+4 weeks', $timezone = null);
        $start = $this->faker->dateTimeThisYear($max = $end, $timezone = null);

        return [
            'scholarship_id' => 1,
            'requirement' => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
            'description' => $this->faker->sentence($nbWords = 20, $variableNbWords = true),
            'promote' => false,
            'enable' => (rand(1, 5) != 5),
            'start_at' => $start,
            'end_at' => $end,
        ];
    }
}
