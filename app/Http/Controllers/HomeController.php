<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Scholarship;
use App\Models\ScholarshipOfficer;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $user = User::with(["scholarship_officers"])->get();
        $checker = ScholarshipOfficer::select('id')->where('user_id',16)->exists();

        print_r($checker);

        // $officers = User::where('usertype', 'officer')->get()->toArray();
        
        // $officers = User::where('usertype', 'officer')->get();

        // // print_r(count($officers));

        // foreach ($officers as $officer) {
        //     print_r($officer);
        //     echo '<br>';
        // }

        // print_r($officers[13]->firstname);

        // $scholarships = Scholarship::all();

        // print_r($scholarships);
    }
}
