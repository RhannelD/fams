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
        // \App\Models\User::factory(10)->create();
        $this->call([
            UserSeeder::class,
            ScholarshipSeeder::class,
            OfficerPositionSeeder::class,
            ScholarshipOfficerSeeder::class,
            ScholarshipScholarSeeder::class,
            ScholarshipPostSeeder::class,
            ScholarshipPostCommentSeeder::class,
            ScholarshipRequirementSeeder::class,
            ScholarshipRequirementAgreementSeeder::class,
            ScholarshipRequirementItemSeeder::class,
            ScholarResponseSeeder::class,
            ScholarResponseAgreementSeeder::class,
            ScholarResponseAnswerSeeder::class,
            ScholarResponseOptionSeeder::class,
            ScholarResponseFileSeeder::class,
            ScholarResponseCommentSeeder::class,
            ScholarCourseSeeder::class,
            ScholarInfoSeeder::class,
            ScholarFacebookSeeder::class,
            UserChatSeeder::class,
        ]);
    }
}
