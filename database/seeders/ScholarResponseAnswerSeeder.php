<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ScholarshipRequirement;
use App\Models\ScholarResponse;
use App\Models\ScholarResponseAnswer;

class ScholarResponseAnswerSeeder extends Seeder
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
                    $query->where('type','question');
                }
            ])
            ->whereHas('items', function ($query) {
                $query->where('type', 'question');
            })
            ->get();

        foreach ($requirements as $requirement) {
            foreach ($requirement->responses as $response) {
                foreach ($requirement->items as $question) {
                    $answer = 'Answer';
                    if ($question->item == 'GWA of previuos semester') {
                        $answer = rand(80,95).'%';
                    } elseif ($question->item == 'No. of units taken') {
                        $answer = rand(15,26).'';
                    }
                    
                    ScholarResponseAnswer::factory()->create([   
                        'response_id' => $response->id,
                        'item_id' => $question->id,
                        'answer' => $answer,
                    ]);
                }
            }
        }
    }
}
