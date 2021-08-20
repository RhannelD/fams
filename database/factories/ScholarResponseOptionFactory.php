<?php

namespace Database\Factories;

use App\Models\ScholarResponseOption;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScholarResponseOptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ScholarResponseOption::class;

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
            'option_id' => 1,
        ];
    }
}
