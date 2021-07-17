<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Scholarship;
use App\Models\ScholarshipPost;

class ScholarshipPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $scholarships = Scholarship::all();

        foreach ($scholarships as $scholarship) {
            ScholarshipPost::factory()->count(rand(10, 20))->create([   
                'scholarship_id' => $scholarship->id,
            ]);
        }
    }
}
