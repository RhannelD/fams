<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Scholarship;
use App\Traits\YearSemTrait;
use App\Models\ScholarshipPost;
use Illuminate\Database\Seeder;

class ScholarshipPostSeeder extends Seeder
{
    use YearSemTrait;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $scholarships = Scholarship::all();
        $officers = User::whereOfficer()->get();

        $acad_years = [
            '2018',
            '2019',
            '2020',
            '2021',
        ];

        $sems = [
            1,
            2,
        ];

        $acad_sem_month = $this->get_acad_sem_month();

        $max_year = $this->get_acad_year();
        $max_sem  = $this->get_acad_sem();

        $first_iteration = true;

        foreach ($acad_years as $acad_year) {
            foreach ($sems as $sem) {
                $acad_sem = $sem=='1'? 'First': 'Second';

                $year      = $sem==1? $acad_year: $acad_year+1;
                $month_day = Carbon::parse($acad_sem_month[$sem])->format('-m-d');
                $date      = $year.$month_day;

                foreach ($scholarships as $scholarship) {
                    $this->create_post_application($scholarship, $officers, $date, $acad_year, $acad_sem, $first_iteration);

                    if ( !$first_iteration ) {
                        $this->create_post_renewal($scholarship, $officers, $date, $acad_year, $acad_sem);
                    }
                }

                if ( $max_year==$acad_year && $max_sem==$sem ) {
                    break;
                }

                if ( $first_iteration ) {
                    $first_iteration = false;
                }
            }
        }
    }

    public function create_post_application(Scholarship $scholarship, $officers, $date, $acad_year, $acad_sem, $first_iteration)
    {
        $acad_year_next = $acad_year+1;
        $date_created = Carbon::parse($date)
            ->addMonths(rand(0, 1))
            ->addDays(rand(3, 30))
            ->addHours(rand(0, 23))
            ->addMinutes(rand(0, 59))
            ->format('Y-m-d h:i:s');

        ScholarshipPost::factory()->create([   
                'user_id' => $officers[(rand(0, (count($officers)-1)))],
                'scholarship_id' => $scholarship->id,
                'title' => "We are looking for".($first_iteration?'':' new')." scholars!",
                'post' => "
                    <h4><strong>We have available slots for {$acad_year}-{$acad_year_next} {$acad_sem} Sem</strong></h4>
                    <p>To those who are looking for scholarship, we have available slots for you. Just accomplish the forms linked down below to submit your requirements.&nbsp;</p>
                    <p>Thank you.</p>
                    ",
                'promote' => true,
                'created_at' => $date_created,
                'updated_at' => $date_created,
            ]);
    }

    public function create_post_renewal(Scholarship $scholarship, $officers, $date, $acad_year, $acad_sem)
    {
        $acad_year_next = $acad_year+1;
        $date_created = Carbon::parse($date)
            ->addMonths(rand(0, 1))
            ->addDays(rand(3, 30))
            ->addHours(rand(0, 23))
            ->addMinutes(rand(0, 59))
            ->format('Y-m-d h:i:s');

        ScholarshipPost::factory()->create([   
            'user_id' => $officers[(rand(0, (count($officers)-1)))],
            'scholarship_id' => $scholarship->id,
            'title' => "Scholarship Renewal for $acad_year-$acad_year_next $acad_sem semester",
            'post' => 'To all of our beloved scholars, you may now submit all of your requirements to your respective links.',
            'created_at' => $date_created,
            'updated_at' => $date_created,
        ]);
    }
}
