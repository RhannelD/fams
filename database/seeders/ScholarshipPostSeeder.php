<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Scholarship;
use App\Models\ScholarshipPost;
use Illuminate\Database\Seeder;

class ScholarshipPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $scholarships = Scholarship::all();

        $month_day_times = [
            '2nd' => [
                'after'  => '-01-01 00:00:00',
                'before' => '-06-30 23:59:59',
            ],
            '1st' => [
                'after'  => '-07-01 00:00:00',
                'before' => '-12-31 23:59:59',
            ],
        ];

        $years = [
            '2018',
            '2019',
            '2020',
            '2021',
        ];

        // For the Renewal Scholars
        foreach ($scholarships as $scholarship) {
            $scholarship_id =  $scholarship->id;
            $officer = User::whereHas('scholarship_officers', function ($query) use ($scholarship_id) {
                    $query->where('scholarship_id', $scholarship_id);
                })->get();

            $year_now = Carbon::today()->format('Y');
            $date_now = Carbon::today()->format('Y-m-d h:i:s');

            foreach ($years as $year) {
                if ( $year_now < $year ) 
                    break;

                foreach ($month_day_times as $semester => $mdt) {
                    $date_after  = $year.$mdt['after'];
                    $date_before = $year.$mdt['before'];

                    if ( $date_now < $date_after ) {
                        break;
                    } elseif ( $date_now < $date_before ) {
                        $date_before = $date_now;
                    }
                    
                    $after  = Carbon::parse($date_after);
                    $before = Carbon::parse($date_before);
                    $random_days = rand(0, $before->diffInDays($after));
                    $random_date = $after->addDays($random_days)
                        ->addHours(rand(0, 10))
                        ->subHour(rand(0, 10))
                        ->addMinutes(rand(0, 59))
                        ->subMinute(rand(0, 59))
                        ->format('Y-m-d h:i:s');
                    
                    $school_year = $semester=='1st'? (int)$year: ((int)$year)-1;
                    $academic_year = $school_year.'-'.($school_year+1);

                    ScholarshipPost::factory()->create([   
                        'user_id' => $officer[(rand(0, (count($officer)-1)))],
                        'scholarship_id' => $scholarship_id,
                        'title' => "Scholarship Renewal for $academic_year $semester semester",
                        'post' => 'To all of our beloved scholars, you may now submit all of your requirements to your respective links.',
                        'created_at' => $random_date,
                        'updated_at' => $random_date,
                    ]);


                    // Just a random post
                    if (rand(0,1) == 0) {
                        $random_date = $after->addDays($random_days+rand(2,7))
                            ->addHours(rand(0, 10))
                            ->subHour(rand(0, 10))
                            ->addMinutes(rand(0, 59))
                            ->subMinute(rand(0, 59))
                            ->format('Y-m-d h:i:s');
                            
                        ScholarshipPost::factory()->create([   
                            'user_id' => $officer[(rand(0, (count($officer)-1)))],
                            'scholarship_id' => $scholarship_id,
                            'title' => "Just a simple announcement!",
                            'created_at' => $random_date,
                            'updated_at' => $random_date,
                        ]);
                    }
                }
            }
        }


        // For new applicants
        foreach ($scholarships as $scholarship) {
            $scholarship_id =  $scholarship->id;
            $officer = User::whereHas('scholarship_officers', function ($query) use ($scholarship_id) {
                    $query->where('scholarship_id', $scholarship_id);
                })->get();

            $date = Carbon::today()->subDays(rand(3, 30))->format('Y-m-d h:i:s');

            ScholarshipPost::factory()->create([   
                'user_id' => $officer[(rand(0, (count($officer)-1)))],
                'scholarship_id' => $scholarship_id,
                'title' => "We are looking for new scholars!",
                'post' => '<h4><strong>We have available slots</strong></h4><p>To those who are looking for scholarship, we have available slots for you. Just accomplish the forms linked down below to submit your requirements.&nbsp;</p><p>Thank you.</p>',
                'promote' => true,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
    }
}
