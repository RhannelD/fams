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
                    'acad_year'     => 2021,
                    'acad_sem'      => 1,
                ]);
            }
        }

        $scholars = User::whereScholar()->where('email', '!=', 'johnravenbalbar@gmail.com')->get();
        foreach ($scholars as $scholar) {
            $rand = rand(1,4)==3? 3: (rand(1,3)==2? 2: 1);
            $scholarships = Scholarship::with('categories')->inRandomOrder()->take($rand)->get();

            foreach ($scholarships as $scholarship) {
                ScholarshipScholar::factory()->create([
                    'user_id'       => $scholar->id,
                    'category_id'   => $scholarship->categories[rand(0, count($scholarship->categories)-1)]->id,
                    'acad_year'     => 2021,
                    'acad_sem'      => 1,
                ]);
            }
        }
    }
}
