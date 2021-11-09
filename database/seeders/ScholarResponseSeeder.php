<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Scholarship;
use App\Models\ScholarResponse;
use Illuminate\Database\Seeder;
use App\Models\ScholarResponseGwa;
use App\Models\ScholarshipScholar;
use App\Models\ScholarResponseUnit;
use App\Models\ScholarshipRequirement;
use App\Models\ScholarshipRequirementCategory;

class ScholarResponseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Response to Requirements for Scholars fake applications of first scholar
        $requirements = ScholarshipRequirement::where('requirement', 'like', "Scholarship Application Form for%")
            ->with([
                'items' => function($query) {
                    $query->whereIn('type',['gwa','units']);
                }
            ])
            ->where('promote', true)
            ->get();
        foreach ($requirements as $requirement) {
            $req_categories = ScholarshipRequirementCategory::where('requirement_id', $requirement->id)->get();
            
            foreach ($req_categories as $req_category) {
                $students = ScholarshipScholar::select('user_id')
                    ->where('category_id', $req_category->category_id)
                    ->get();

                $min = strtotime($requirement->start_at);
                $max = strtotime($requirement->end_at);
                
                foreach ($students as $student) {
                    // Inserts all of the approved scholars
                    $ran = rand($min, $max);
                    
                    $response = ScholarResponse::factory()->create([   
                        'user_id' => $student->user_id,
                        'requirement_id' => $requirement->id,
                        'approval' => true,
                        'submit_at' => date('Y-m-d H:i:s', $ran),
                    ]);

                    foreach ($requirement->items as $item) {
                        if ( $item->type == 'gwa' ) {
                            ScholarResponseGwa::factory()->create([   
                                'response_id' => $response->id,
                                'item_id' => $item->id,
                                'gwa' => rand(125, 250)*0.01,
                            ]);
                        } elseif ( $item->type == 'units' ) {
                            ScholarResponseUnit::factory()->create([   
                                'response_id' => $response->id,
                                'item_id' => $item->id,
                                'units' => rand(16, 26),
                            ]);
                        }
                    }
                }

                $students = User::whereScholar()
                    ->whereNotScholarOf($requirement->scholarship_id)
                    ->get();

                foreach ($students as $student) {
                    // Inserts random denied response of other scholars
                    if ( rand(0, 5) != 1 ) 
                        continue;
                    
                    $ran = rand($min, $max);
                    $response = ScholarResponse::factory()->create([   
                        'user_id' => $student->id,
                        'requirement_id' => $requirement->id,
                        'approval' => false,
                        'submit_at' => date('Y-m-d H:i:s', $ran),
                    ]);
                    
                    foreach ($requirement->items as $item) {
                        if ( $item->type == 'gwa' ) {
                            ScholarResponseGwa::factory()->create([   
                                'response_id' => $response->id,
                                'item_id' => $item->id,
                                'gwa' => rand(251, 300)*0.01,
                            ]);
                        } elseif ( $item->type == 'units' ) {
                            ScholarResponseUnit::factory()->create([   
                                'response_id' => $response->id,
                                'item_id' => $item->id,
                                'units' => rand(10, 17),
                            ]);
                        }
                    }
                }
            }
        }
        // -------------------------------------------------------------

        // Response to Requirements for renewals which are all approved
        $requirements = ScholarshipRequirement::where('promote', false)
            ->with([
                'items' => function($query) {
                    $query->whereIn('type',['gwa','units']);
                }
            ])
            ->get();
        foreach ($requirements as $requirement) {
            $req_categories = ScholarshipRequirementCategory::where('requirement_id', $requirement->id)->get();

            foreach ($req_categories as $req_category) { 
                $students = ScholarshipScholar::select('user_id')
                    ->where('category_id', $req_category->category_id)
                    ->get();

                $min = strtotime($requirement->start_at);
                $max = strtotime($requirement->end_at);

                foreach ($students as $student) {
                    // Inserts all of the approved scholar renewal
                    $ran = rand($min, $max);
                    
                    $response = ScholarResponse::factory()->create([   
                        'user_id' => $student->user_id,
                        'requirement_id' => $requirement->id,
                        'approval' => true,
                        'submit_at' => date('Y-m-d H:i:s', $ran),
                    ]);

                    foreach ($requirement->items as $item) {
                        if ( $item->type == 'gwa' ) {
                            ScholarResponseGwa::factory()->create([   
                                'response_id' => $response->id,
                                'item_id' => $item->id,
                                'gwa' => rand(125, 250)*0.01,
                            ]);
                        } elseif ( $item->type == 'units' ) {
                            ScholarResponseUnit::factory()->create([   
                                'response_id' => $response->id,
                                'item_id' => $item->id,
                                'units' => rand(16, 26),
                            ]);
                        }
                    }
                }
            }
        }
        // -------------------------------------------------------------

        // Response to Currrent Requirements for Scholars where is pending
        $requirements = ScholarshipRequirement::where('requirement', 'like', "Application Form for%")
            ->with([
                'items' => function($query) {
                    $query->whereIn('type',['gwa','units']);
                }
            ])
            ->where('promote', true)
            ->get();
        foreach ($requirements as $requirement) {
            $req_categories = ScholarshipRequirementCategory::where('requirement_id', $requirement->id)->get();

            $min = strtotime($requirement->start_at);
            $end_at = Carbon::parse($requirement->end_at)->format('Y-m-d H:i:s') < Carbon::now()->format('Y-m-d H:i:s')
                ? $requirement->end_at
                : Carbon::now()->format('Y-m-d H:i:s');
            $max = strtotime($end_at);

            foreach ($req_categories as $req_category) {
                $students = User::whereScholar()
                    ->whereNotScholarOf($requirement->scholarship_id)
                    ->get();

                foreach ($students as $student) {
                    // Inserts random pending response of other scholars
                    if ( rand(0, 7) != 6 ) 
                        continue;
                    
                    $ran = rand($min, $max);
                    $response = ScholarResponse::factory()->create([   
                        'user_id' => $student->id,
                        'requirement_id' => $requirement->id,
                        'approval' => null,
                        'submit_at' => date('Y-m-d H:i:s', $ran),
                    ]);

                    foreach ($requirement->items as $item) {
                        $high = rand(0,1) == 0;
                        if ( $item->type == 'gwa' ) {
                            ScholarResponseGwa::factory()->create([   
                                'response_id' => $response->id,
                                'item_id' => $item->id,
                                'gwa' => $high? rand(125, 250)*0.01: rand(251, 300)*0.01,
                            ]);
                        } elseif ( $item->type == 'units' ) {
                            ScholarResponseUnit::factory()->create([   
                                'response_id' => $response->id,
                                'item_id' => $item->id,
                                'units' => $high? rand(16, 26): rand(14, 17),
                            ]);
                        }
                    }
                }
            }
        }
    }
}
