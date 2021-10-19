<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\UserChat;
use Illuminate\Database\Seeder;

class UserChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::where('id', '!=', 1)->get();

        $users_count = count($users);
        for ($mes_num=0; $mes_num < rand(7, 14); $mes_num++) { 
            $user1_id = 1;
            $user2_id = $users[rand(0, $users_count-1)]->id;
            $date = Carbon::now();

            for ($chat_count=0; $chat_count < rand(5, 20); $chat_count++) { 
                $sender_1 = rand(0, 1);

                UserChat::factory()->create([
                    'sender_id'   => ($sender_1)? $user1_id: $user2_id,
                    'receiver_id' => (!$sender_1)? $user1_id: $user2_id,
                    'seen'        => $date,
                ]);
            }
        }
    }
}
