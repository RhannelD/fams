<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ScholarResponse;
use App\Models\ScholarshipRequirement;
use App\Models\ScholarshipScholar;

class ScholarResponseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $requirements = ScholarshipRequirement::with('categories')
            ->get();


        foreach ($requirements as $requirement) {
            // Create random responses from existing scholars
            if (!$requirement->promote) {
                foreach ($requirement->categories as $category) {
                    $students = ScholarshipScholar::select('user_id')
                        ->where('category_id', $category->category_id)
                        ->get();
    
                    $min = strtotime($requirement->start_at);
                    $max = strtotime($requirement->end_at);
    
                    foreach ($students as $student) {
                        $val = rand($min, $max);
                        
                        ScholarResponse::factory()->create([   
                            'user_id' => $student->user_id,
                            'requirement_id' => $requirement->id,
                            'approval' => (rand(1, 5) != 5),
                            'submit_at' => date('Y-m-d H:i:s', $val)
                        ]);
                    }
                }
                continue;
            }

            // Create random responses both from some of existing and not scholars
            if ($requirement->categories->count() != 0) {
                $categories = [];

                foreach ($requirement->categories as $category) {
                    array_push($categories, $category->category_id);

                    if (rand(1, 5) != 5) {
                        continue;
                    }

                    $students = ScholarshipScholar::select('user_id')
                        ->where('category_id', $category->category_id)
                        ->get();
    
                    $min = strtotime($requirement->start_at);
                    $max = strtotime($requirement->end_at);
    
                    foreach ($students as $student) {
                        $val = rand($min, $max);
                        
                        ScholarResponse::factory()->create([   
                            'user_id' => $student->user_id,
                            'requirement_id' => $requirement->id,
                            'approval' => true,
                            'submit_at' => date('Y-m-d H:i:s', $val)
                        ]);
                    }
                }

                if (count($categories) != 0) {
                    $students = User::select('users.id')
                        ->leftJoin(with(new ScholarshipScholar)->getTable(), 'scholarship_scholars.user_id', '=', 'users.id')
                        ->where('usertype', 'scholar')
                        ->whereNotIn('category_id', $categories)
                        ->get();

                    $min = strtotime($requirement->start_at);
                    $max = strtotime($requirement->end_at);
    
                    foreach ($students as $student) {
                        if (rand(1, 5) != 4) {
                            continue;
                        }

                        $val = rand($min, $max);
                        
                        ScholarResponse::factory()->create([   
                            'user_id' => $student->id,
                            'requirement_id' => $requirement->id,
                            'approval' => false,
                            'submit_at' => date('Y-m-d H:i:s', $val)
                        ]);
                    }
                }

            }
        }
    }
}
