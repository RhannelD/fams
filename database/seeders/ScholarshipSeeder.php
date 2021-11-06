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
                'scholarship'=>'TES (Tertiary Education Subsidy)',
                'categories' =>[
                    [
                        'category'=> 'TES',
                        'amount'  => 20000,
                    ],
                ],
            ],
            [
                'scholarship'=>'TES-TDP (Tulong Dunong Program)',
                'categories' =>[
                    [
                        'category'=> '6k',
                        'amount'  => 6000,
                    ],
                    [
                        'category'=> '7.5k',
                        'amount'  => 7500,
                    ],
                ],
            ],
            [
                'scholarship'=>'BFAR',
                'categories' =>[
                    [ 
                        'category'=> 'BFAR',
                        'amount'  => 20000,
                    ],
                ],
            ],
            [
                'scholarship'=>'CHED Half Merit',
                'categories' =>[
                    [ 
                        'category'=> 'CHED',
                        'amount'  => 10000,
                    ],
                ],
            ],
            [
                'scholarship'=>'PLDT Gabay Guro',
                'categories' =>[
                    [ 
                        'category'=> 'PLDT',
                        'amount'  => 17500,
                    ],
                ],
            ],
            [
                'scholarship'=>'DOST',
                'categories' =>[
                    [
                        'category'=> 'DOST',
                        'amount'  => 1,
                    ],
                ],
            ],
            [
                'scholarship'=>'Sakamoto',
                'categories' =>[
                    [ 
                        'category'=> 'Sakamoto',
                        'amount'  => 25000,
                    ],
                ],
            ],
            [
                'scholarship'=>'LCCKFI (Luis Co Chi Kat Foundation Inc.)',
                'categories' =>[
                    [ 
                        'category'=> 'LCCKFI',
                        'amount'  => 5000,
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
