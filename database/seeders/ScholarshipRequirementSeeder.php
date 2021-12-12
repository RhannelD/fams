<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Scholarship;
use Illuminate\Support\Str;
use App\Traits\YearSemTrait;
use App\Models\ScholarResponse;
use App\Models\ScholarshipPost;
use Illuminate\Database\Seeder;
use App\Models\ScholarshipScholar;
use App\Models\ScholarshipCategory;
use App\Models\ScholarshipRequirement;
use App\Models\ScholarshipPostLinkRequirement;
use App\Models\ScholarshipRequirementCategory;

class ScholarshipRequirementSeeder extends Seeder
{
    use YearSemTrait;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = ScholarshipPost::orderBy('created_at')->get();

        foreach ($posts as $post) {
            $acad_year = $this->get_acad_year(Carbon::parse($post->created_at));
            $acad_sem  = $this->get_acad_sem(Carbon::parse($post->created_at));

            foreach ($post->scholarship->categories as $category) {
                if ( Str::contains($post->title, 'Scholarship Renewal for' ) ) {
                    $this->add_renewal_req_with_previous_scholars($post, $category, $acad_year, $acad_sem);
                } elseif ( $post->title == 'We are looking for scholars!' ) {
                    $this->add_application_req_with_first_scholars($post, $category, $acad_year, $acad_sem);
                } else {
                    $this->add_application_req_with_next_scholars($post, $category, $acad_year, $acad_sem);
                }
            }
        }

        
        $current_acad_year = $this->get_acad_year();
        $current_acad_sem  = $this->get_acad_sem();

        $requirements = ScholarshipRequirement::where('acad_year', $current_acad_year)
            ->where('acad_sem', $current_acad_sem)
            ->get();

        foreach ($requirements as $requirement) {
            if ( rand(0,5) == 1 ) {
                continue;
            }

            $requirement->end_at = Carbon::now()->addDays(rand(6,14))->format('Y-m-d h:i:s');
            $requirement->save();
        }
    }

    public function add_application_req_with_first_scholars(ScholarshipPost $post, ScholarshipCategory $category, $acad_year, $acad_sem)
    {
        $scholars = User::whereScholar()
            ->whereNotScholarOf($post->scholarship_id, Carbon::parse($post->created_at))
            ->inRandomOrder()
            ->take(rand(30, 40))
            ->get();

        foreach ($scholars as $scholar) {
            ScholarshipScholar::factory()->create([
                'user_id'       => $scholar->id,
                'category_id'   => $category->id,
                'acad_year'     => $acad_year,
                'acad_sem'      => $acad_sem,
            ]);
        }

        $requirement = ScholarshipRequirement::factory()->create([   
                'scholarship_id' => $post->scholarship_id,
                'requirement' => "Scholarship Application Form for $category->category",
                'description' => "<h3>$category->category Applicants</h3> You submit your requirements here.",
                'promote' => true,
                'enable' => null,
                'acad_year' => $acad_year,
                'acad_sem' => $acad_sem,
                'start_at' => $post->created_at,
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

        $this->add_req_scholar_responses($requirement, $scholars);
    }

    public function add_renewal_req_with_previous_scholars(ScholarshipPost $post, ScholarshipCategory $category, $acad_year, $acad_sem)
    {
        $category_id    = $category->id;
        $prev_acad_year = $acad_sem=='1'? $acad_year-1: $acad_year;
        $prev_acad_sem  = $acad_sem=='1'? '2': '1';

        $scholars = User::whereScholar()
            ->whereHas('scholarship_scholars', function ($query) use ($category_id, $prev_acad_year, $prev_acad_sem) {
                $query->whereYearSem($prev_acad_year, $prev_acad_sem)
                    ->where('category_id', $category_id);
            })
            ->inRandomOrder()
            ->get();

        $count_of_accepted = rand(35, 45);
        $denied_scholars = [];
        
        foreach ($scholars as $key => $scholar) {
            if ( $count_of_accepted>$key ) {
                ScholarshipScholar::factory()->create([
                    'user_id'       => $scholar->id,
                    'category_id'   => $category->id,
                    'acad_year'     => $acad_year,
                    'acad_sem'      => $acad_sem,
                ]);
            } else {
                $denied_scholars[] = $scholar->id;
            }
        }

        $requirement = ScholarshipRequirement::factory()->create([   
                'scholarship_id' =>  $post->scholarship_id,
                'requirement' => "$category->category - {$post->title}",
                'description' => "<h1>$category->category Scholars</h1> You submit your requirements here.",
                'promote' => false,
                'enable' => null,
                'acad_year' => $acad_year,
                'acad_sem' => $acad_sem,
                'start_at' => $post->created_at,
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

        $this->add_req_scholar_responses($requirement, $scholars, $denied_scholars);
    }
    
    public function add_application_req_with_next_scholars(ScholarshipPost $post, ScholarshipCategory $category, $acad_year, $acad_sem)
    {
        $scholarship_id = $post->scholarship_id;
        $prev_acad_year = $acad_sem=='1'? $acad_year-1: $acad_year;
        $prev_acad_sem  = $acad_sem=='1'? '2': '1';

        $scholars = User::whereScholar()
            ->whereNotScholarOf($post->scholarship_id, Carbon::parse($post->created_at))
            ->whereDoesntHave('scholarship_scholars', function ($query) use ($scholarship_id, $prev_acad_year, $prev_acad_sem) {
                $query->whereYearSem($prev_acad_year, $prev_acad_sem)
                    ->whereHas('category', function ($query) use ($scholarship_id) {
                        $query->where('scholarship_id', '=', $scholarship_id);
                    });
            })
            ->inRandomOrder()
            ->take(rand(10, 15))
            ->get();

        foreach ($scholars as $scholar) {
            ScholarshipScholar::factory()->create([
                'user_id'       => $scholar->id,
                'category_id'   => $category->id,
                'acad_year'     => $acad_year,
                'acad_sem'      => $acad_sem,
            ]);
        }

        $requirement = ScholarshipRequirement::factory()->create([   
                'scholarship_id' => $post->scholarship_id,
                'requirement' => "Scholarship Application Form for $category->category",
                'description' => "<h3>$category->category Applicants</h3> You submit your requirements here.",
                'promote' => true,
                'enable' => null,
                'acad_year' => $acad_year,
                'acad_sem' => $acad_sem,
                'start_at' => $post->created_at,
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

        $this->add_req_scholar_responses($requirement, $scholars);
    }

    public function add_req_scholar_responses(ScholarshipRequirement $requirement, $scholars, $denied_scholars = null)
    {
        foreach ($scholars as $key => $scholar) {
            $after  = Carbon::parse($requirement->start_at);
            $before = Carbon::parse($requirement->end_at);
            $random_days = rand(1, $before->diffInDays($after));

            $random_date = $after->addDays($random_days)->subHour(rand(0, 10))->subMinute(rand(0, 59));

            ScholarResponse::factory()->create([   
                'user_id' => $scholar->id,
                'requirement_id' => $requirement->id,
                'approval' => is_null($denied_scholars)? true: !in_array($scholar->id , $denied_scholars),
                'submit_at' => $random_date->format('Y-m-d h:i:s'),
            ]);
        }
    }
}
