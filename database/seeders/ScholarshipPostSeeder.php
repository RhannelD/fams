<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Scholarship;
use App\Models\ScholarshipPost;

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

        foreach ($scholarships as $scholarship) {
            $officer = User::select('users.*')
                ->join('scholarship_officers', 'users.id', '=', 'scholarship_officers.user_id')
                ->where('scholarship_id', $scholarship->id)
                ->get();

            for ($count=0; $count < rand(10, 20); $count++) {
                ScholarshipPost::factory()->create([   
                    'user_id' => $officer[(rand(0, (count($officer)-1)))],
                    'scholarship_id' => $scholarship->id,
                ]);
            }
        }
    }
}
