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
        $officers = User::where('usertype', 'officer')->get();

        $scholarships = Scholarship::all();

        $fill_admins = true;
        $officers_count = count($officers);
        $officers_index = 0;

        while ($officers_index < $officers_count) { 
            foreach ($scholarships as $scholarship) {
                if ($fill_admins) {
                    ScholarshipOfficer::factory()->create([   
                        'user_id'       => $officers[$officers_index]->id,
                        'scholarship_id'=> $scholarship->id,
                        'position_id'   => 1,
                    ]);

                    $officers_index++;
                    continue;
                }

                ScholarshipOfficer::factory()->create([   
                    'user_id'       => $officers[$officers_index]->id,
                    'scholarship_id'=> $scholarship->id,
                ]);
                $officers_index++;
            }

            $fill_admins = false;
        }
    }
}
