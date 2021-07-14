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
            $item_COR = new ScholarshipRequirementItem;
            $item_COR->requirement_id  = $requirement->id;
            $item_COR->item = 'Certificate of Registration';
            $item_COR->note = 'Upload in PDF file';
            $item_COR->type = 'cor';
            $item_COR->position = 1;
            $item_COR->save();
            
            $item_Grade = new ScholarshipRequirementItem;
            $item_Grade->requirement_id  = $requirement->id;
            $item_Grade->item = 'Previuos Semester Grades';
            $item_Grade->note = 'Upload in PDF file';
            $item_Grade->type = 'grade';
            $item_Grade->position = 2;
            $item_Grade->save();

            for ($items=0; $items < rand(5,8); $items++) { 
                ScholarshipRequirementItem::factory()->create([   
                    'requirement_id' => $requirement->id,
                    'position' => ($items+3)
                ]);
            }
        }
    }
}
