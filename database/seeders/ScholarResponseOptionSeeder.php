<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ScholarshipRequirement;
use App\Models\ScholarshipRequirementItemOption;
use App\Models\ScholarResponse;
use App\Models\ScholarResponseOption;

class ScholarResponseOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Radio type option
        $requirements = ScholarshipRequirement::query()
            ->with('responses')
            ->with(array('items' => function($query) {
                $query->where('type', 'radio');
            }))
            ->whereHas('items', function ($query) {
                $query->where('type', 'radio');
            })
            ->get();

        foreach ($requirements as $requirement) {
            foreach ($requirement->responses as $response) {
                foreach ($requirement->items as $item) {
                    $option = ScholarshipRequirementItemOption::select('id')
                        ->where('item_id', $item->id)
                        ->inRandomOrder()
                        ->first();

                    ScholarResponseOption::factory()->create([   
                        'response_id' => $response->id,
                        'option_id' => $option->id,
                    ]);
                }
            }
        }

        // Check box type option
        $requirements = ScholarshipRequirement::query()
            ->with('responses')
            ->with(array('items' => function($query) {
                $query->where('type', 'check');
            }))
            ->whereHas('items', function ($query) {
                $query->where('type', 'check');
            })
            ->get();

        foreach ($requirements as $requirement) {
            foreach ($requirement->responses as $response) {
                foreach ($requirement->items as $item) {
                    $options = ScholarshipRequirementItemOption::select('id')
                        ->where('item_id', $item->id)
                        ->limit(rand(1, 4))
                        ->get();

                    foreach ($options as $option ) {
                        ScholarResponseOption::factory()->create([   
                            'response_id' => $response->id,
                            'item_id' => $item->id,
                            'option_id' => $option->id,
                        ]);
                    }
                }
            }
        }
    }
}
