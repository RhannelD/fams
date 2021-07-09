<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Scholarship;
use App\Models\ScholarshipOfficer;
use Illuminate\Support\Facades\DB;

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
        $search = '';
        $scholars = DB::table('scholarship_scholars')->select('*')
            ->join('users', 'users.id', '=', 'scholarship_scholars.user_id')
            ->where('usertype', 'scholar')
            ->where(function ($query) use ($search) {
                $query->where('firstname', 'like', "%$search%")
                    ->orWhere('middlename', 'like', "%$search%")
                    ->orWhere('lastname', 'like', "%$search%")
                    ->orWhere(DB::raw('CONCAT(firstname, " ", lastname)'), 'like', "%$search%");
            })->get();
        
        echo $scholars;

        // $user = Scholarship::all();
        // $scholars = DB::table('users AS u')
        //     ->select(
        //         DB::raw('DISTINCT(COUNT(u.id)) as scholarships'), 
        //         function ($query) {
        //             $query->selectRaw('COUNT(u2.id)')
        //                 ->from('users as u2')
        //                 ->whereRaw(DB::raw('COUNT(u.id)'),
        //                     function ($query2) {
        //                         $query2->selectRaw('COUNT(u2.id)')
        //                             ->from('scholarship_scholars AS ss2')
        //                             ->whereRaw(DB::raw('ss2.user_id'), 'u2.id');
        //                     }
        //                 );
        //         }
        //     )
        //     ->join('scholarship_scholars AS ss', 'u.id', '=', 'ss.user_id')
        //     ->where('usertype', 'scholar')
        //     ->groupBy('u.id')
        //     ->get();

        // $scholars =  DB::select('SELECT DISTINCT(COUNT(u.id)) as acquired, (
        //     SELECT COUNT(u2.id)
        //     FROM users u2
        //     WHERE (COUNT(u.id)) = (
        //         SELECT COUNT(u2.id)
        //         FROM scholarship_scholars ss2
        //         WHERE ss2.user_id = u2.id
        //         )
        //     ) AS counts
        // FROM users u
        //     INNER JOIN scholarship_scholars ss ON u.id = ss.user_id 
        // GROUP BY u.id');

        // $acquired = [];
        // $counts = [];
        // foreach ($scholars as $scholar) {
        //     array_push($acquired, $scholar->acquired);
        //     array_push($counts, $scholar->counts);
        // }

        // return ['acquired' => $acquired, 'counts' => $counts];
        // print_r($user[1]);

        // $user = User::with(["scholarship_officers"])->get();
        // $checker = ScholarshipOfficer::select('id')->where('user_id',16)->exists();

        // print_r($checker);

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
