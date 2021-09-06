<?php

namespace Database\Factories;

use App\Models\ScholarResponseAgreement;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScholarResponseAgreementFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ScholarResponseAgreement::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'response_id' => 1,
            'agreement_id' => 1,
        ];
    }
}
