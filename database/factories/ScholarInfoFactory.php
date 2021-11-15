<?php

namespace Database\Factories;

use App\Models\ScholarInfo;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScholarInfoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ScholarInfo::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $occupations = [
            'Accountant',
            'Agriculturist',
            'Accounting staff',
            'Air-conditioning technician',
            'Animator',
            'Carpenter',
            'Cashier',
            'Civil engineer',
            'Chemical engineer',
            'Computer programmer',
            'Draftsman',
            'Electrical engineer',
            'Electrical technician',
            'Florist',
            'Food technologist',
            'Forester',
            'Geodetic engineer',
            'Heavy equipment operator',
            'Herbologist',
            'Human resource manager',
            'IT specialist',
            'Industrial engineer',
            'Instrumentation technician',
            'Landscape artist',
            'Legal transcriptionist',
            'Librarian',
            'Machinist',
            'Mason',
            'Materials engineer',
            'Mechanical engineer',
            'Medical technologist',
            'Metallurgist',
            'Nutritionist',
            'Painter',
            'Pharmacist',
            'Physician',
            'Plumber',
            'Sanitary engineer',
            'Software developer',
            'Systems analyst',
            'Veterinarian',
            'Web designer',
            'Welder',
        ];

        $educational_attainments = [
            'elementary',
            'high school',
            'college',
        ];

        $occupations_count = count($occupations)-1;
        $educational_attainments_count = count($educational_attainments)-1;

        $mother_living = !(rand(1, 10) == 9);
        $father_living = !(rand(1, 10) == 9);
        
        return [
            'year' => rand(3, 4),
            'mother_name' => $this->faker->name('female'),
            'mother_birthday' => $this->faker->dateTimeBetween($startDate = '-50 years', $endDate = '-30 years', $timezone = null)->format('Y-m-d'),
            'mother_occupation' => $mother_living? $occupations[rand(0, $occupations_count)]: '',
            'mother_living' => $mother_living,
            'mother_educational_attainment' => $educational_attainments[rand(0, $educational_attainments_count)],
            'father_name' => $this->faker->name('female'),
            'father_birthday' => $this->faker->dateTimeBetween($startDate = '-50 years', $endDate = '-30 years', $timezone = null)->format('Y-m-d'),
            'father_occupation' => $father_living? $occupations[rand(0, $occupations_count)]: '',
            'father_living' => $father_living,
            'father_educational_attainment' => $educational_attainments[rand(0, $educational_attainments_count)],
        ];
    }
}
