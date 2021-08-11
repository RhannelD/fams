<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ScholarshipRequirement;
use App\Models\ScholarResponse;
use App\Models\ScholarResponseFile;

class ScholarResponseFileSeeder extends Seeder
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
            ->with(array('items' => function($query) {
                $query->whereIn('type', ['cor', 'grade', 'file']);
            }))
            ->whereHas('items', function ($query) {
                $query->whereIn('type', ['cor', 'grade', 'file']);
            })
            ->get();

        foreach ($requirements as $requirement) {
            foreach ($requirement->responses as $response) {
                foreach ($requirement->items as $item) {
                    ScholarResponseFile::factory()->create([   
                        'response_id' => $response->id,
                        'item_id' => $item->id,
                    ]);
                }
            }
        }
    }
}
