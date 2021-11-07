<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ScholarResponseUnit;
use App\Models\ScholarshipRequirement;

class ScholarResponseUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $requirements = ScholarshipRequirement::query()
            ->with('responses')
            ->with([
                'items' => function($query) {
                    $query->where('type','units');
                }
            ])
            ->whereHas('items', function ($query) {
                $query->where('type', 'units');
            })
            ->get();

        foreach ($requirements as $requirement) {
            foreach ($requirement->responses as $response) {
                foreach ($requirement->items as $question) {
                    ScholarResponseUnit::factory()->create([   
                        'response_id' => $response->id,
                        'item_id' => $question->id,
                    ]);
                }
            }
        }
    }
}
