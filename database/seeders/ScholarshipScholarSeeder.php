<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Scholarship;
use App\Models\ScholarshipScholar;
use App\Models\ScholarshipCategory;
use App\Models\User;

class ScholarshipScholarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $scholars = User::where('usertype', 'scholar')->get();

        $scholarships = Scholarship::all();

        foreach ($scholars as $scholar) {
            if (rand(1, 10) == 1) {
                continue;
            }

            foreach ($scholarships as $scholarship) {
                if (rand(1, 4) == 1) {
                    $categories = ScholarshipCategory::where('scholarship_id', $scholarship->id)->get();

                    $random_id = rand(0, 2);
                    if (isset($categories[$random_id])) {
                        ScholarshipScholar::factory()->create([   
                            'user_id'       => $scholar->id,
                            'category_id'   => $categories[$random_id]->id,
                        ]);
                    }                    
                }
            }
        }
    }
}
