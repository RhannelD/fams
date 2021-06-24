<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

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

        for ($i=0; $i < 30; $i++) { 
            DB::table('users')->insert([   
                'usertype' => 'scholar',
                'firstname' => Str::random(10),
                'middlename' => Str::random(10),
                'lastname' => Str::random(10),
                'gender' => 'male',
                'phone' => Str::random(10),
                'email' => Str::random(10).'@gmail.com',
                'password' => Hash::make('password'),
                'birthday' => '1900-01-01',
            ]);
        }
        
    }
}
