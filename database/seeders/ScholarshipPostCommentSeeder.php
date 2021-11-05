<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\ScholarshipPost;
use Illuminate\Database\Seeder;
use App\Models\ScholarshipPostComment;

class ScholarshipPostCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = ScholarshipPost::all();

        foreach ($posts as $post) {
            $scholarship_id = $post->scholarship_id;
            $users = User::whereOfficer()
                ->orWhere(function ($query) use ($scholarship_id) {
                    $query->whereScholarOf($scholarship_id);
                })
                ->get();

            $post_created_at = Carbon::parse($post->created_at);

            for ($comment_count=0; $comment_count < rand(0, 30); $comment_count++) { 
                $post_created_at = $post_created_at
                    ->addHours(rand(0, 10))
                    ->addMinutes(rand(0, 59));

                $date = $post_created_at->format('Y-m-d h:i:s');
                ScholarshipPostComment::factory()->create([   
                    'user_id' => $users[rand(0, (count($users)-1))]->id,
                    'post_id' => $post->id,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }
    }
}
