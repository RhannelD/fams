<?php

namespace Database\Factories;

use App\Models\ScholarResponseFile;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScholarResponseFileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ScholarResponseFile::class;

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
            'file_url' => 'fakefile.pdf',
            'file_name' => 'Temporary File.pdf',
        ];
    }
}
