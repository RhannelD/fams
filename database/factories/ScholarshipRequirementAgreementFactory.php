<?php

namespace Database\Factories;

use App\Models\ScholarshipRequirementAgreement;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScholarshipRequirementAgreementFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ScholarshipRequirementAgreement::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $paragraphs = $this->faker->paragraphs(rand(2, 6));
        $title = $this->faker->realText(50);
        $post = "<h3>{$title}</h3>";
        foreach ($paragraphs as $para) {
            $post .= "<p>{$para}</p>";
        }

        return [
            'requirement_id' => 1,
            'agreement' => $post,
        ];
    }
}
