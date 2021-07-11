<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Scholarship;
use App\Models\ScholarshipRequirement;
use Carbon\Carbon;

class ScholarshipRequirementSeeder extends Seeder
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
            $not_promoted_yet = true;
            for ($i=0; $i < rand(5,8); $i++) { 
                $to_promote = false;

                if ($not_promoted_yet) {
                    $not_promoted_yet = (rand(0,5) != 4);
                    if (!$not_promoted_yet) {
                        $to_promote = true;
                    }
                }

                ScholarshipRequirement::factory()->create([   
                    'scholarship_id' => $scholarship->id,
                    'promote' => $to_promote,
                ]);
            }
        }
    }
}
