<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Scholarship;
use App\Models\ScholarshipPost;
use Illuminate\Database\Seeder;
use App\Models\ScholarshipCategory;
use App\Models\ScholarshipRequirement;
use App\Models\ScholarshipPostLinkRequirement;
use App\Models\ScholarshipRequirementCategory;

class ScholarshipRequirementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $scholarships = Scholarship::with('categories')->get();

        // Requirements for Scholars fake applications of first scholar
        foreach ($scholarships as $scholarship) {
            $posts = ScholarshipPost::where('scholarship_id', $scholarship->id)
                ->where('title', 'We are looking for new scholars!')
                ->orderBy('id')
                ->take(1)
                ->get();

            foreach ($posts as $post) {
                $post_created_at = Carbon::parse($post->created_at);
                $date = $post_created_at->format('Y-m-d h:i:s');

                foreach ($scholarship->categories as $category) {
                    $requirement = ScholarshipRequirement::factory()->create([   
                        'scholarship_id' => $scholarship->id,
                        'requirement' => "Scholarship Application Form for $category->category",
                        'description' => "<h3>$category->category Applicants</h3> You submit your requirements here.",
                        'promote' => true,
                        'enable' => null,
                        'acad_year' => '2018',
                        'acad_sem' => '1',
                        'start_at' => $date,
                        'end_at' => Carbon::parse($post->created_at)->addWeek(2)->format('Y-m-d h:i:s'),
                    ]);

                    ScholarshipPostLinkRequirement::factory()->create([   
                        'post_id' => $post->id,
                        'requirement_id' => $requirement->id,
                    ]);

                    ScholarshipRequirementCategory::factory()->create([   
                        'requirement_id' => $requirement->id,
                        'category_id' => $category->id,
                    ]);
                }           
            }
        }

        // Requirements for Renewal
        foreach ($scholarships as $scholarship) {
            $categories = ScholarshipCategory::where('scholarship_id', $scholarship->id)->get();
            $posts = ScholarshipPost::where('scholarship_id', $scholarship->id)
                ->where('title', 'like', 'Scholarship Renewal for%')
                ->get();

            foreach ($posts as $post) {
                $post_created_at = Carbon::parse($post->created_at);
                $date = $post_created_at->format('Y-m-d h:i:s');

                foreach ($categories as $category) {
                    $requirement = ScholarshipRequirement::factory()->create([   
                        'scholarship_id' => $scholarship->id,
                        'requirement' => "$category->category - {$post->title}",
                        'description' => "<h1>$category->category Scholars</h1> You submit your requirements here.",
                        'start_at' => $date,
                        'enable' => null,
                        'acad_year' => $post_created_at->format('Y'),
                        'acad_sem' => '1',
                        'end_at' => Carbon::parse($post->created_at)->addWeek(2)->format('Y-m-d h:i:s'),
                    ]);

                    ScholarshipPostLinkRequirement::factory()->create([   
                        'post_id' => $post->id,
                        'requirement_id' => $requirement->id,
                    ]);
                    
                    ScholarshipRequirementCategory::factory()->create([   
                        'requirement_id' => $requirement->id,
                        'category_id' => $category->id,
                    ]);
                }           
            }
        }

        // Requirements for Applicants
        foreach ($scholarships as $scholarship) {
            $categories = ScholarshipCategory::where('scholarship_id', $scholarship->id)->get();
            $posts = ScholarshipPost::where('scholarship_id', $scholarship->id)
                ->where('title', 'We are looking for new scholars!')
                ->orderBy('id', 'desc')
                ->take(1)
                ->get();

            foreach ($posts as $post) {
                $post_created_at = Carbon::parse($post->created_at);

                foreach ($categories as $category) {
                    $requirement = ScholarshipRequirement::factory()->create([   
                        'scholarship_id' => $scholarship->id,
                        'requirement' => "Application Form for $category->category",
                        'description' => "<h3>$category->category Applicants</h3> You submit your requirements here.",
                        'promote' => true,
                        'enable' => null,
                        'acad_year' => '2021',
                        'acad_sem' => '1',
                        'start_at' => $post_created_at->format('Y-m-d h:i:s'),
                        'end_at'   => $post_created_at->addMonth(rand(0,2))->addWeek(rand(2,4))->format('Y-m-d h:i:s'),
                    ]);

                    ScholarshipPostLinkRequirement::factory()->create([   
                        'post_id' => $post->id,
                        'requirement_id' => $requirement->id,
                    ]);

                    ScholarshipRequirementCategory::factory()->create([   
                        'requirement_id' => $requirement->id,
                        'category_id' => $category->id,
                    ]);
                }           
            }
        }
    }
}
