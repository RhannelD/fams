<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ScholarshipRequirement;
use App\Models\ScholarshipRequirementAgreement;

class ScholarshipRequirementAgreementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $requirements = ScholarshipRequirement::all();

        foreach ($requirements as $requirement) {
            if ( rand(0, 4) == 0 )
                continue;

            ScholarshipRequirementAgreement::factory()->create([   
                'requirement_id' => $requirement->id,
            ]);
        }
    }
}
