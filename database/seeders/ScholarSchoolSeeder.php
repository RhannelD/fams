<?php

namespace Database\Seeders;

use App\Models\ScholarSchool;
use Illuminate\Database\Seeder;

class ScholarSchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $schools = [
            [ 
                'school'    => 'Batangas State University', 
                'location'  => 'Batangas City, Batangas', 
            ],
            [ 
                'school'    => 'Batangas State University - Alangilan Campus', 
                'location'  => 'Batangas City, Batangas', 
            ],
            [ 
                'school'    => 'Batangas State University - Malvar Campus', 
                'location'  => 'Malvar, Batangas', 
            ],
            [ 
                'school'    => 'Batangas State University - Lipa Campus', 
                'location'  => 'Lipa City, Batangas', 
            ],
            [ 
                'school'    => 'Batangas State University - Nasugbu Campus', 
                'location'  => 'Nasugbu, Batangas', 
            ],
            [ 
                'school'    => 'Batangas State University - Rosario Campus', 
                'location'  => 'Rosario, Batangas', 
            ],
            [ 
                'school'    => 'Batangas State University - Lemery Campus', 
                'location'  => 'Lemery, Batangas', 
            ],
            [ 
                'school'    => 'Batangas State University - Balayan Campus', 
                'location'  => 'Balayan, Batangas', 
            ],
            [ 
                'school'    => 'AMA Computer University - Lipa Campus', 
                'location'  => 'Lipa City, Batangas', 
            ],
            [ 
                'school'    => 'First Asia Institute of Technology and Humanities', 
                'location'  => 'Tanauan City, Batangas', 
            ],
            [ 
                'school'    => 'Nova Schola Tanauan', 
                'location'  => 'Tanauan City, Batangas', 
            ],
            [ 
                'school'    => 'AMA Computer University - Batangas Campus', 
                'location'  => 'Batangas City, Batangas', 
            ],
            [ 
                'school'    => 'Lyceum of the Philippines University - Batangas Campus', 
                'location'  => 'Batangas City, Batangas', 
            ],
            [ 
                'school'    => 'University of Batangas', 
                'location'  => 'Batangas City, Batangas', 
            ],
            [ 
                'school'    => 'STI College - Lipa', 
                'location'  => 'Lipa City, Batangas', 
            ],
            [ 
                'school'    => 'Polytechnic University of the Philippines - Sto. Tomas', 
                'location'  => 'Sto. Tomas, Batangas', 
            ],
            [ 
                'school'    => 'Saint Bridget College', 
                'location'  => 'Batangas City, Batangas', 
            ],
        ];

        foreach($schools as $school){
            ScholarSchool::create($school);
        }
    }
}
