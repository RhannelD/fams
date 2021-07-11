<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ScholarshipRequirement;
use App\Models\ScholarshipRequirementItem;

class ScholarshipRequirementItemSeeder extends Seeder
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
            for ($items=0; $items < rand(5,8); $items++) { 
                ScholarshipRequirementItem::factory()->create([   
                    'requirement_id' => $requirement->id,
                ]);
            }
        }
    }
}
