<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ScholarInfo;
use App\Models\ScholarCourse;
use App\Models\ScholarSchool;
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
        $schools = ScholarSchool::all();
        $courses = ScholarCourse::all();
        $scholars = User::whereScholar()->get();

        $schools_count = $schools->count()-1;
        $courses_count = $courses->count()-1;

        foreach ($scholars as $scholar) {
            $info = ScholarInfo::factory()->create([
                'user_id'   => $scholar->id,
                'school_id' => $schools[rand(0, $schools_count)]->id,
                'course_id' => $courses[rand(0, $courses_count)]->id,
                'semester' => 1,
            ]);
        }
    }
}
