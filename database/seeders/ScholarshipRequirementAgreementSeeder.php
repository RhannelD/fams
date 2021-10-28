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
                'agreement' => "
                    <h3>This is an angreeement</h3>
                    Upon submitting this requirement you are agreeing with the terms.
                    <ul>
                        <li>
                            If we verified that you are lying about your documents, this will potentially lose your scholarship here.
                        </li>
                        <li>
                            You will lose your scholarship when you are using multiple accounts to acquire multiple scholarships.
                        </li>
                    </ul>
                ",
            ]);
        }
    }
}
