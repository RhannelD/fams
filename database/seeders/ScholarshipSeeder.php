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
                'link' => 'https://web.facebook.com/unifastofficial',
                'categories' =>[
                    [
                        'category'=> 'TES',
                        'amount'  => 20000,
                    ],
                ],
            ],
            [
                'scholarship'=>'TES-TDP (Tulong Dunong Program)',
                'link' => 'https://web.facebook.com/unifastofficial',
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
                'link' => 'https://www.bfar.da.gov.ph/scholarship.jsp',
                'categories' =>[
                    [ 
                        'category'=> 'BFAR',
                        'amount'  => 20000,
                    ],
                ],
            ],
            [
                'scholarship'=>'CHED Half Merit',
                'link' => 'https://web.facebook.com/groups/CHEDscholarship/',
                'categories' =>[
                    [ 
                        'category'=> 'CHED',
                        'amount'  => 10000,
                    ],
                ],
            ],
            [
                'scholarship'=>'PLDT Gabay Guro',
                'link' => 'https://www.gabayguro.com/pillars-of-learning/scholarships/',
                'categories' =>[
                    [ 
                        'category'=> 'PLDT',
                        'amount'  => 17500,
                    ],
                ],
            ],
            [
                'scholarship'=>'DOST',
                'link' => 'https://web.facebook.com/groups/DOSTscholarship/',
                'categories' =>[
                    [
                        'category'=> 'DOST',
                        'amount'  => 1,
                    ],
                ],
            ],
            [
                'scholarship'=>'Sakamoto',
                'link' => 'https://www.sy-kogyo.co.jp/english/company/index.html',
                'categories' =>[
                    [ 
                        'category'=> 'Sakamoto',
                        'amount'  => 25000,
                    ],
                ],
            ],
            [
                'scholarship'=>'LCCKFI (Luis Co Chi Kat Foundation Inc.)',
                'link' => 'https://web.facebook.com/lcckfi/',
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
                'scholarship' => $data['scholarship'],
                'link' => $data['link'],
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
