<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([   
            'usertype' => 'admin',
            'firstname' => 'Rhannel',
            'middlename' => 'Dalida',
            'lastname' => 'Dinlasan',
            'gender' => 'male',
            'birthday' => '1999-12-07',
            'phone' => '09351458776',
            'email' => 'rhanneldinlasan@gmail.com',
            'password' => Hash::make('123123123'),
        ]);

        User::factory()->count(100)->create();

    }
}
