<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ScholarResponse;
use App\Models\ScholarResponseComment;
use App\Models\ScholarshipOfficer;
use App\Models\ScholarshipRequirement;

class ScholarResponseCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $responses = ScholarResponse::all();

        foreach ($responses as $response) {
            $officer = User::whereOfficer()->inRandomOrder()->first();
            
            for ($comment_count=0; $comment_count < rand(3, 15); $comment_count++) { 
                $user_id = (rand(0, 1) == 1)?  $officer->id: $response->user_id;

                ScholarResponseComment::factory()->create([   
                    'user_id' => $user_id,
                    'response_id' => $response->id,
                ]);
            }
        }
    }
}
