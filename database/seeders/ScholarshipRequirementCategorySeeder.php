<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ScholarshipCategory;
use App\Models\ScholarshipRequirement;
use App\Models\ScholarshipRequirementCategory;

class ScholarshipRequirementCategorySeeder extends Seeder
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
            $categories = ScholarshipCategory::where('scholarship_id', $requirement->scholarship_id)->get();
            $random = rand(0, (count($categories)-1));

            ScholarshipRequirementCategory::factory()->create([   
                'requirement_id' => $requirement->id,
                'category_id' => $categories[$random]->id
            ]);
        }
    }
}
