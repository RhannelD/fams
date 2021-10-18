<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ScholarFacebook;
use Illuminate\Database\Seeder;

class ScholarFacebookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $scholars = User::whereScholar()->get();

        foreach ($scholars as $scholar) {
            $name = preg_replace('/\s+/', '', $scholar->fmlname());
            $info = ScholarFacebook::factory()->create([
                'user_id'   => $scholar->id,
                'facebook_link' => "https://www.facebook.com/{$name}",
            ]);
        }
    }
}
