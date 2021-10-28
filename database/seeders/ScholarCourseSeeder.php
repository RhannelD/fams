<?php

namespace Database\Seeders;

use App\Models\ScholarCourse;
use Illuminate\Database\Seeder;

class ScholarCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $courses = [
            [ 'course' => 'Bachelor of Science in Accountancy', ],
            [ 'course' => 'Bachelor of Science in Business Administration major in Marketing Management'],
            [ 'course' => 'Bachelor of Science in Tourism Management'],
            [ 'course' => 'Bachelor of Science in Hospitality Management'],
            [ 'course' => 'Bachelor of Science in Business Administration major in Human Resource Management'],
            [ 'course' => 'Bachelor of Science in Criminology'],
            [ 'course' => 'Bachelor of Science in Fisheries and Aquatic Sciences'],
            [ 'course' => 'Bachelor of Science in Computer Science'],
            [ 'course' => 'Bachelor of Science in Information Technology'],
            [ 'course' => 'Bachelor of Science in Computer Engineering'],
            [ 'course' => 'Bachelor of Science in Food Technology'],
            [ 'course' => 'Bachelor of Industrial Technology'],
            [ 'course' => 'Bachelor of Science in Nursing'],
            [ 'course' => 'Bachelor of Science in Nutrition and Dietetics'],
            [ 'course' => 'Bachelor of Secondary Education major in English'],
            [ 'course' => 'Bachelor of Secondary Education major in Mathematics'],
            [ 'course' => 'Bachelor of Secondary Education major in Sciences'],
        ];

        foreach($courses as $course){
            ScholarCourse::create($course);
        }
    }
}
