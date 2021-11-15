<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ScholarInfo;
use App\Models\ScholarCourse;
use Illuminate\Database\Seeder;

class ScholarInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $courses = ScholarCourse::all();
        $scholars = User::whereScholar()->get();

        $courses_count = $courses->count()-1;

        $code = 10000;
        foreach ($scholars as $scholar) {
            $info = ScholarInfo::factory()->create([
                'user_id'   => $scholar->id,
                'course_id' => $courses[rand(0, $courses_count)]->id,
                'semester' => 1,
                'srcode' => "17-".($code++),
            ]);
        }
    }
}
