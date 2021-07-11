<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ScholarshipRequirementItem;
use App\Models\ScholarshipRequirementItemOption;

class ScholarshipRequirementItemOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = ScholarshipRequirementItem::where('type', 'check')
            ->orWhere('type', 'radio')->get();

        foreach ($items as $item) {
            for ($options=0; $options < rand(2,4); $options++) { 
                ScholarshipRequirementItemOption::factory()->create([   
                    'item_id' => $item->id,
                ]);
            }
        }
    }
}
