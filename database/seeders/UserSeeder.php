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
            [   
                'usertype'      => 'admin',
                'firstname'     => 'Rhannel',
                'middlename'    => 'Dalida',
                'lastname'      => 'Dinlasan',
                'gender'        => 'male',
                'religion'      => 'Roman Catholic',
                'birthday'      => '1999-12-07',
                'birthplace'    => 'Biga, Calatagan, Batangas',
                'address'       => 'Biga, Calatagan, Batangas',
                'phone'         => '09351458776',
                'email'         => 'rhanneldinlasan@gmail.com',
                'password'      => Hash::make('123123123'),
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [   
                'usertype'      => 'officer',
                'firstname'     => 'John Raven',
                'middlename'    => 'Rodriguez',
                'lastname'      => 'Balbar',
                'gender'        => 'male',
                'religion'      => 'Roman Catholic',
                'birthday'      => '2000-09-17',
                'birthplace'    => 'Cumba, Lian , Batangas',
                'address'       => 'Cumba, Lian , Batangas',
                'phone'         => '09368638084',
                'email'         => 'johnravenbalbar@gmail.com',
                'password'      => Hash::make('123123123'),
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [   
                'usertype'      => 'officer',
                'firstname'     => 'Hazel',
                'middlename'    => 'Gal',
                'lastname'      => 'Gonzales',
                'gender'        => 'female',
                'religion'      => 'Roman Catholic',
                'birthday'      => '1999-10-23',
                'birthplace'    => 'Tanagan, Calatagan, Batangas',
                'address'       => 'Tanagan, Calatagan, Batangas',
                'phone'         => '09664554812',
                'email'         => 'hazelgalgonzales9@gmail.com',
                'password'      => Hash::make('123123123'),
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [   
                'usertype'      => 'scholar',
                'firstname'     => 'Ren',
                'middlename'    => 'Eroles',
                'lastname'      => 'Dalida',
                'gender'        => 'male',
                'religion'      => 'Roman Catholic',
                'birthday'      => '1999-11-23',
                'birthplace'    => 'Biga, Calatagan, Batangas',
                'address'       => 'Biga, Calatagan, Batangas',
                'phone'         => '09664250790',
                'email'         => 'rendalida@gmail.com',
                'password'      => Hash::make('123123123'),
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
        ]);

        $officers = [
            [
                'firstname' => 'Alzeo',
                'lastname' => 'Landicho',
                'gender' => 'male',
                'email' => 'alzeo.landicho@example.com',
            ],
            [
                'firstname' => 'Alvin',
                'lastname' => 'Dalida',
                'gender' => 'male',
                'email' => 'alvin.dalida@example.com',
            ],
            [
                'firstname' => 'Analy',
                'lastname' => 'Dalida',
                'gender' => 'female',
                'email' => 'analy.dalida@example.com',
            ],
            [
                'firstname' => 'Jaypee',
                'lastname' => 'Venzon',
                'gender' => 'male',
                'email' => 'jaypee.venzon@example.com',
            ],
            [
                'firstname' => 'Daisy',
                'lastname' => 'Elposar',
                'gender' => 'male',
                'email' => 'daisy.elposar@example.com',
            ],
            [
                'firstname' => 'Nikki Sofia',
                'lastname' => 'Mailig',
                'gender' => 'female',
                'email' => 'nikkisofia.mailig@example.com',
            ],
            [
                'firstname' => 'Ryan',
                'lastname' => 'De Luna',
                'gender' => 'male',
                'email' => 'ryan.deluna@example.com',
            ],
            [
                'firstname' => 'Jayrald',
                'lastname' => 'Holgado',
                'gender' => 'male',
                'email' => 'jayrald.holgado@example.com',
            ],
        ];

        foreach ($officers as $key => $officer) {
            User::factory()->create([
                'usertype' => 'officer',
                'firstname' => $officer['firstname'],
                'lastname' => $officer['lastname'],
                'gender' => $officer['gender'],
                'email' => $officer['email'],
            ]);
        }

        $scholars = [
            [
                'firstname' => 'Ian Glenn',
                'lastname' => 'Mercado',
                'gender' => 'male',
                'email' => 'ianglenn.mercado@example.com',
            ],
            [
                'firstname' => 'Judith',
                'lastname' => 'Manalo',
                'gender' => 'female',
                'email' => 'judith.manalo@example.com',
            ],
            [
                'firstname' => 'John Paul',
                'lastname' => 'Macalindong',
                'gender' => 'male',
                'email' => 'johnpaul.macalindong@example.com',
            ],
            [
                'firstname' => 'Ronniel',
                'lastname' => 'Buhay',
                'gender' => 'male',
                'email' => 'ronniel.buhay@example.com',
            ],
            [
                'firstname' => 'Jhanzen',
                'lastname' => 'Lopez',
                'gender' => 'male',
                'email' => 'jhanzen.lopez@example.com',
            ],
            [
                'firstname' => 'John Alex',
                'lastname' => 'Binuya',
                'gender' => 'male',
                'email' => 'johnalex.binuya@example.com',
            ],
            [
                'firstname' => 'Ma. Vanessa',
                'lastname' => 'Bengcang',
                'gender' => 'female',
                'email' => 'mavanessa.bengcang@example.com',
            ],
            [
                'firstname' => 'Angelo Kim',
                'lastname' => 'Espina',
                'gender' => 'male',
                'email' => 'angelokim.espina@example.com',
            ],
            [
                'firstname' => 'Aila Mei',
                'lastname' => 'Atienza',
                'gender' => 'female',
                'email' => 'ailamei.atienza@example.com',
            ],
            [
                'firstname' => 'Lemuel',
                'lastname' => 'Capacia',
                'gender' => 'male',
                'email' => 'lemuel.capacia@example.com',
            ],
            [
                'firstname' => 'April Rose',
                'lastname' => 'Catena',
                'gender' => 'female',
                'email' => 'aprilrose.catena@example.com',
            ],
            [
                'firstname' => 'Jean Dale',
                'lastname' => 'Malabanan',
                'gender' => 'female',
                'email' => 'jeandale.malabanan@example.com',
            ],
            [
                'firstname' => 'Nel',
                'lastname' => 'Tolentino',
                'gender' => 'male',
                'email' => 'nel.tolentino@example.com',
            ],
            [
                'firstname' => 'Brandon',
                'lastname' => 'Cabatian',
                'gender' => 'male',
                'email' => 'brandon.cabatin@example.com',
            ],
            [
                'firstname' => 'Ailyn',
                'lastname' => 'Data',
                'gender' => 'female',
                'email' => 'ailyn.data@example.com',
            ],
            [
                'firstname' => 'Dhenmark',
                'lastname' => 'Ignaco',
                'gender' => 'male',
                'email' => 'dhenmark.ignaco@example.com',
            ],
            [
                'firstname' => 'Aldwin',
                'lastname' => 'Zafra',
                'gender' => 'male',
                'email' => 'aldwin.zafra@example.com',
            ],
            [
                'firstname' => 'Weljun',
                'lastname' => 'De Liola',
                'gender' => 'male',
                'email' => 'weljun.deliola@example.com',
            ],
            [
                'firstname' => 'Noel',
                'lastname' => 'Carandang',
                'gender' => 'male',
                'email' => 'noel.carandang@example.com',
            ],
            [
                'firstname' => 'Dharwyn',
                'lastname' => 'Buhay',
                'gender' => 'male',
                'email' => 'dharwyn.buhay@example.com',
            ],
            [
                'firstname' => 'Edward',
                'lastname' => 'Erna',
                'gender' => 'male',
                'email' => 'edward.erna@example.com',
            ],
            [
                'firstname' => 'Alexandra Gheil',
                'lastname' => 'Emaas',
                'gender' => 'female',
                'email' => 'alexandragheil.emaas@example.com',
            ],
            [
                'firstname' => 'Rizzalyn',
                'lastname' => 'Ramirez',
                'gender' => 'female',
                'email' => 'rizzalyn.ramirez@example.com',
            ],
            [
                'firstname' => 'Nicole',
                'lastname' => 'Bascon',
                'gender' => 'female',
                'email' => 'nicole.bascon@example.com',
            ],
            [
                'firstname' => 'Iver',
                'lastname' => 'Mulingbayan',
                'gender' => 'female',
                'email' => 'iver.mulingbayan@example.com',
            ],
            [
                'firstname' => 'Karen',
                'lastname' => 'Bayaborda',
                'gender' => 'female',
                'email' => 'karen.bayaborda@example.com',
            ],
            [
                'firstname' => 'Aljhon',
                'lastname' => 'Mulingbayan',
                'gender' => 'male',
                'email' => 'aljhon.mulingbayan@example.com',
            ],
            [
                'firstname' => 'Francis',
                'lastname' => 'Andino',
                'gender' => 'male',
                'email' => 'francis.andino@example.com',
            ],
            [
                'firstname' => 'Jalem',
                'lastname' => 'Manalastas',
                'gender' => 'male',
                'email' => 'jalem.manalastas@example.com',
            ],
            [
                'firstname' => 'Salvador',
                'lastname' => 'Todoc',
                'gender' => 'male',
                'email' => 'salvador.todoc@example.com',
            ],
            [
                'firstname' => 'Janine',
                'lastname' => 'Cornejo',
                'gender' => 'female',
                'email' => 'janine.cornejo@example.com',
            ],
            [
                'firstname' => 'Menalyn',
                'lastname' => 'Mendoza',
                'gender' => 'female',
                'email' => 'menalyn.mendoza@example.com',
            ],
            [
                'firstname' => 'Alfred',
                'lastname' => 'Mendoza',
                'gender' => 'male',
                'email' => 'alfred.mendoza@example.com',
            ],
            [
                'firstname' => 'Kent Arvin',
                'lastname' => 'Berbie',
                'gender' => 'male',
                'email' => 'kentarvin.berbie@example.com',
            ],
            [
                'firstname' => 'John Neri',
                'lastname' => 'Comeso',
                'gender' => 'male',
                'email' => 'johnneri.comeso@example.com',
            ],
            [
                'firstname' => 'Neil',
                'lastname' => 'Daria',
                'gender' => 'male',
                'email' => 'neil.daria@example.com',
            ],
            [
                'firstname' => 'Marcel',
                'lastname' => 'Argente',
                'gender' => 'female',
                'email' => 'marcel.argente@example.com',
            ],
            [
                'firstname' => 'Alyssa',
                'lastname' => 'Rada',
                'gender' => 'female',
                'email' => 'alyssa.rada@example.com',
            ],
            [
                'firstname' => 'Orlando',
                'lastname' => 'Dilag',
                'gender' => 'male',
                'email' => 'orlando.dilag@example.com',
            ],
            [
                'firstname' => 'Melissa',
                'lastname' => 'Erna',
                'gender' => 'female',
                'email' => 'melissa.erna@example.com',
            ],
            [
                'firstname' => 'Keyzy Rose',
                'lastname' => 'Dime',
                'gender' => 'female',
                'email' => 'keyzyrose.dime@example.com',
            ],
            [
                'firstname' => 'Jessa ',
                'lastname' => 'Mendoza',
                'gender' => 'female',
                'email' => 'jessa.mendoza@example.com',
            ],
            [
                'firstname' => 'Kimberly',
                'lastname' => 'Oronico',
                'gender' => 'female',
                'email' => 'kimberly.oronico@example.com',
            ],
            [
                'firstname' => 'John Kevin',
                'lastname' => 'Canha',
                'gender' => 'male',
                'email' => 'johnkevin.canha@example.com',
            ],
            [
                'firstname' => 'Kenneth',
                'lastname' => 'Varias',
                'gender' => 'male',
                'email' => 'kenneth.varias@example.com',
            ],
            [
                'firstname' => 'Rizza',
                'lastname' => 'Frane',
                'gender' => 'female',
                'email' => 'rizza.frane@example.com',
            ],
            [
                'firstname' => 'Jessie',
                'lastname' => 'Andino',
                'gender' => 'female',
                'email' => 'jessie.andino@example.com',
            ],
            [
                'firstname' => 'Clarissa',
                'lastname' => 'Santos',
                'gender' => 'female',
                'email' => 'clarissa.santos@example.com',
            ],
            [
                'firstname' => 'Ryan',
                'lastname' => 'De Guzman',
                'gender' => 'male',
                'email' => 'ryan.deguzman@example.com',
            ],
            [
                'firstname' => 'Realyn',
                'lastname' => 'Mercado',
                'gender' => 'female',
                'email' => 'realyn.mercado@example.com',
            ],
            [
                'firstname' => 'Donnalyn',
                'lastname' => 'Cirilo',
                'gender' => 'female',
                'email' => 'donnalyn.cirilo@example.com',
            ],
            [
                'firstname' => 'Michael',
                'lastname' => 'Dimaala',
                'gender' => 'male',
                'email' => 'michael.dimaala@example.com',
            ],
        ];

        foreach ($scholars as $key => $scholar) {
            User::factory()->create([
                'firstname' => $scholar['firstname'],
                'lastname' => $scholar['lastname'],
                'gender' => $scholar['gender'],
                'email' => $scholar['email'],
            ]);
        }
        // User::factory()->count(67)->create();
    }
}
