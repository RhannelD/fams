<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            ScholarCourseSeeder::class,
            ScholarInfoSeeder::class,
            ScholarFacebookSeeder::class,
            ScholarshipSeeder::class,
            ScholarshipPostSeeder::class,
            ScholarshipRequirementSeeder::class,
            ScholarshipPostCommentSeeder::class,
            ScholarshipRequirementAgreementSeeder::class,
            ScholarshipRequirementItemSeeder::class,
            ScholarResponseSeeder::class,
            ScholarResponseAgreementSeeder::class,
            ScholarResponseAnswerSeeder::class,
            ScholarResponseOptionSeeder::class,
            ScholarResponseFileSeeder::class,
            ScholarResponseCommentSeeder::class,
            UserChatSeeder::class,
        ]);
    }
}
