<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $gender = $this->faker->randomElement(['male', 'female']);
        return [
            'usertype' => 'scholar',
            'firstname' => $this->faker->firstName,
            'middlename' => $this->faker->lastname,
            'lastname' => $this->faker->lastname,
            'gender' => $gender,
            'phone' => Str::random(10),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('123123123'),
            'birthday' => $this->faker->date($format = 'Y-m-d', $max = '2015-12-01'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
