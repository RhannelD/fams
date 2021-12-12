<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Scholarship;
use App\Traits\YearSemTrait;
use App\Models\ScholarResponse;
use Illuminate\Database\Seeder;
use App\Models\ScholarResponseGwa;
use App\Models\ScholarshipScholar;
use App\Models\ScholarResponseUnit;
use App\Models\ScholarshipRequirement;
use App\Models\ScholarshipRequirementCategory;

class ScholarResponseSeeder extends Seeder
{
    use YearSemTrait;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $requirements = ScholarshipRequirement::with([
                'items' => function($query) {
                    $query->whereIn('type',['gwa','units']);
                }
            ])
            ->get();

        foreach ($requirements as $requirement) {
            foreach ($requirement->responses as $response) {
                foreach ($requirement->items as $item) {
                    if ( $response->approval == true ) {
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
                    } elseif ( $response->approval == false ) {
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

        $acad_year = $this->get_acad_year();
        $acad_sem  = $this->get_acad_sem();

        $responses_to_unevaluate = ScholarResponse::whereHas('requirement', function ($query) use ($acad_year, $acad_sem) {
                $query->where('acad_year', $acad_year)
                    ->where('acad_sem', $acad_sem);
            })
            ->inRandomOrder()
            ->get();

        foreach ($responses_to_unevaluate as $response) {
            if ( rand(0,8) == 1 ) {
                if ( $response->approval ) {
                    ScholarshipScholar::where('user_id', $response->user_id)
                        ->where('category_id', $response->requirement->categories[0]->category_id)
                        ->where('acad_year', $response->requirement->acad_year)
                        ->where('acad_sem', $response->requirement->acad_sem)
                        ->delete();
                }
                
                $response->approval = null;
                $response->save();
            }
        }
    }
}
