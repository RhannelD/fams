<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ScholarshipPost;
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
            $officers = User::select('users.*')
                ->join('scholarship_officers', 'users.id', '=', 'scholarship_officers.user_id')
                ->where('scholarship_officers.scholarship_id', $post->scholarship_id);

            $users = User::select('users.*')
                ->join('scholarship_scholars', 'users.id', '=', 'scholarship_scholars.user_id')
                ->join('scholarship_categories', 'scholarship_scholars.category_id', '=', 'scholarship_categories.id')
                ->where('scholarship_categories.scholarship_id', $post->scholarship_id)
                ->union($officers)
                ->get();

            for ($comment_count=0; $comment_count < rand(0, 30); $comment_count++) { 
                ScholarshipPostComment::factory()->create([   
                    'user_id' => $users[rand(0, (count($users)-1))]->id,
                    'post_id' => $post->id,
                ]);
            }
        }
    }
}
