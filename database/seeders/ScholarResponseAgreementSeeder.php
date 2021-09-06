<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ScholarshipRequirement;
use App\Models\ScholarResponse;
use App\Models\ScholarResponseAgreement;

class ScholarResponseAgreementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $requirements = ScholarshipRequirement::has('agreements')->get();

        foreach ($requirements as $requirement) {
            $agreement_id = $requirement->agreements->first()->id;

            foreach ($requirement->responses as $response) {
                ScholarResponseAgreement::factory()->create([   
                    'response_id' => $response->id,
                    'agreement_id' => $agreement_id,
                ]);
            }
        }
    }
}
