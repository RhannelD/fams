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
        User::insert([   
            'usertype'      => 'admin',
            'firstname'     => 'Rhannel',
            'middlename'    => 'Dalida',
            'lastname'      => 'Dinlasan',
            'gender'        => 'male',
            'religion'      => 'Roman Catholic',
            'birthday'      => '1999-12-07',
            'birthplace'    => 'Biga, Calatagan Batangas',
            'phone'         => '09351458776',
            'email'         => 'rhanneldinlasan@gmail.com',
            'password'      => Hash::make('123123123'),
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        User::factory()->count(15)->create(['usertype' => 'officer']);

        User::factory()->count(85)->create();

    }
}
