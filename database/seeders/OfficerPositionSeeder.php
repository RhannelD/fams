<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OfficerPosition;
use Carbon\Carbon;

class OfficerPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OfficerPosition::insert([   
            'position'      => 'Admin',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        
        OfficerPosition::insert([   
            'position'      => 'Officer',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        OfficerPosition::insert([   
            'position'      => 'None',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
    }
}
