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
        $scholarships = Scholarship::with('categories')->get();

        $test_scholar = User::whereScholar()->where('email', 'johnravenbalbar@gmail.com')->first();
        if ( isset($test_scholar) ) {
            for ($index=0; $index < 2; $index++) { 
                ScholarshipScholar::factory()->create([   
                    'user_id'       => $test_scholar->id,
                    'category_id'   => $scholarships[($index==0? $index: $index+1)]->categories[0]->id,
                ]);
            }
        }

        $scholars = User::whereScholar()->where('email', '!=', 'johnravenbalbar@gmail.com')->get();

        foreach ($scholars as $scholar) {
            if (rand(1, 12) == 1) {
                continue;
            }

            foreach ($scholarships as $scholarship) {
                if (rand(1, 4) == 1) {    
                    ScholarshipScholar::factory()->create([
                        'user_id'       => $scholar->id,
                        'category_id'   => $scholarship->categories[rand(0, count($scholarship->categories)-1)]->id,
                    ]);
                }
            }
        }
    }
}
