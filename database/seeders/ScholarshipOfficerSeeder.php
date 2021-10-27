<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Scholarship;
use App\Models\ScholarshipOfficer;

class ScholarshipOfficerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $officers = User::whereOfficer()->get();

        $scholarships = Scholarship::all();
        $scholarships_count = count($scholarships);

        $index = 0;
        foreach ($officers as $key => $officer) {
            if ( $index >= $scholarships_count ) 
                break;
            
            $user_id = $officer->id;
            ScholarshipOfficer::factory()->create([   
                'user_id'       => $user_id,
                'scholarship_id'=> $scholarships[$index]->id,
                'position_id'   => 1,
            ]);
            
            if ( in_array($user_id, [2, 3]) ) {
                ScholarshipOfficer::factory()->create([   
                    'user_id'       => $user_id,
                    'scholarship_id'=> $scholarships[$index+1]->id,
                ]);
            }
            $index++;
        }

        $officers = User::whereOfficer()->doesntHave('scholarship_officers')->get();
        $officers_count = count($officers);
        for ($index=0; $index < $officers_count; $index++) { 
            foreach ($scholarships as $scholarship) {
                if ( !($index < $officers_count) ) 
                    break;

                ScholarshipOfficer::factory()->create([   
                    'user_id'       => $officers[$index]->id,
                    'scholarship_id'=> $scholarship->id,
                ]);
                $index++;
            }
        }
    }
}
