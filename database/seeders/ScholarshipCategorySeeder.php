<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ScholarshipCategory;
use App\Models\Scholarship;

class ScholarshipCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $scholarships = Scholarship::all();

        for ($num=0; $num < 3; $num++) { 
            foreach ($scholarships as $scholarship) {
                if ($num != 0 && rand(1,3) == 1) {
                    continue;
                }

                ScholarshipCategory::factory()->create([   
                    'scholarship_id'=> $scholarship->id,
                ]);
            }
        }
    }
}
