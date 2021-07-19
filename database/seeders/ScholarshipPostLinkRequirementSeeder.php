<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Scholarship;
use App\Models\ScholarshipPost;
use App\Models\ScholarshipPostLinkRequirement;
use App\Models\ScholarshipRequirement;

class ScholarshipPostLinkRequirementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $scholarships = Scholarship::all();

        foreach ($scholarships as $scholarship) {
    
            $requirements = ScholarshipRequirement::where('scholarship_id', $scholarship->id)
                ->orderBy('id', 'desc')
                ->get();

            $posts = ScholarshipPost::where('scholarship_id', $scholarship->id)->get();

            foreach ($posts as $post) {
                
                if ( rand(0, 3) != 0 ) {
                    continue;
                }

                $added_requirements = [];
                for ($count=0; $count < rand(1, 2); $count++) { 
                    
                    $requirement_id = null;
                    do {
                        $requirement_id = rand(0, (count($requirements)-1));
                    } while ( in_array($requirement_id, $added_requirements) );
                    array_push($added_requirements, $requirement_id);
                    
                    ScholarshipPostLinkRequirement::factory()->create([   
                        'post_id' => $post->id,
                        'requirement_id' => $requirements[$requirement_id],
                    ]);

                }

            }

        }
    }
}
