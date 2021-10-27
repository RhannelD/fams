<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Scholarship;
use Illuminate\Database\Seeder;
use App\Models\ScholarshipCategory;

class ScholarshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $scholarships = [
            [
                'scholarship'=>'Mayor Antonio Jose A. Barcelon Educational Assistance Program',
                'categories' =>[
                    [
                        'category'=> 'MAAEBP',
                        'amount'  => 5000,
                    ],
                ],
            ],
            [
                'scholarship'=>'Provincial Governor Scholarship',
                'categories' =>[
                    [
                        'category'=> 'Public',
                        'amount'  => 3000,
                    ],
                    [
                        'category'=> 'Private',
                        'amount'  => 5000,
                    ],
                    [
                        'category'=> 'High Honors',
                        'amount'  => 8000,
                    ],
                ],
            ],
            [
                'scholarship'=>'Sugar Industry Foundation, Inc',
                'categories' =>[
                    [
                        'category'=> 'LUZONFED',
                        'amount'  => 7500,
                    ],
                    [
                        'category'=> 'CADPI',
                        'amount'  => 7500,
                    ],
                    [
                        'category'=> 'RFI',
                        'amount'  => 7500,
                    ],
                ],
            ],
            [
                'scholarship'=>'Cong. Eileen Ermita-Buhain Scholars',
                'categories' =>[
                    [ 
                        'category'=> 'EEB Scholars',
                        'amount'  => 7500,
                    ],
                ],
            ],
        ];

        foreach ($scholarships as $key => $data) {
            $scholarship = Scholarship::factory()->create([
                'scholarship' => $data['scholarship']
            ]);
            foreach ($data['categories'] as $key => $category) {
                ScholarshipCategory::factory()->create([   
                    'scholarship_id'=> $scholarship->id,
                    'category'      => $category['category'],
                    'amount'        => $category['amount'],
                ]);
            }
        }
    }
}
