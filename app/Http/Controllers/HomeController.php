<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Scholarship;
use App\Models\ScholarshipOfficer;
use App\Models\ScholarshipRequirement;
use App\Models\ScholarshipRequirementItem;
use App\Models\ScholarshipRequirementItemOption;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        echo Carbon::now()->addDay()->format('Y-m-d H:i:s').'<br>';
        return Carbon::now()->format('Y-m-d H:i:s');

        // $position = ScholarshipRequirementItem::where('requirement_id', 1)
        //     ->max('position');

        // return $position+1;

        // $options = ScholarshipRequirementItemOption::select('id')
        //     ->where('item_id', 218)->get();

        // return $options;

        // $items = ScholarshipRequirementItem::select('id')
        //     ->where('requirement_id', 1)
        //     ->get();

        // $item = ScholarshipRequirementItem::select('id')
        //     ->where('id', 30)
        //     ->first();

        // // $items->setAttribute(, $item);
        // $items[count($items)] = $item;
        // // $items[count($items)] = (object) ["id" => '100'];

        // foreach ($items as $key => $value) {
        //     echo gettype($value).'<br>';
        //     echo $key.'  '.$value.'<br>'; 
        // }

        // return  $items;

        // $requirement_items =  ScholarshipRequirementItem::where('requirement_id', 2)
        //     ->get();

        // foreach ($requirement_items as $key => $requirement_item) {
        //     if (in_array($requirement_item->type, array('radio', 'check'))) {
        //         $options = ScholarshipRequirementItemOption::where('item_id', $requirement_item->id)->get();

        //         $requirement_items[$key]['options'] = $options;
        //     }
        // }

        // return ($requirement_items);

        // $search = '';

        // $officers = User::where('usertype', 'officer')
        //     ->join('scholarship_officers', 'users.id', '=', 'scholarship_officers.user_id')
        //     ->join('officer_positions', 'scholarship_officers.position_id', '=', 'officer_positions.id')
        //     ->where('scholarship_id', 1)
        //     ->where(function ($query) use ($search) {
        //         $query->where('firstname', 'like', "%$search%")
        //             ->orWhere('middlename', 'like', "%$search%")
        //             ->orWhere('lastname', 'like', "%$search%")
        //             ->orWhere(DB::raw('CONCAT(firstname, " ", lastname)'), 'like', "%$search%");
        //     })->get();

        // return $officers;

        // return route('scholarship.program', [1, 'home']);

        // $search = '';
        // $scholars = DB::table('scholarship_scholars')->select('*')
        //     ->join('users', 'users.id', '=', 'scholarship_scholars.user_id')
        //     ->where('usertype', 'scholar')
        //     ->where(function ($query) use ($search) {
        //         $query->where('firstname', 'like', "%$search%")
        //             ->orWhere('middlename', 'like', "%$search%")
        //             ->orWhere('lastname', 'like', "%$search%")
        //             ->orWhere(DB::raw('CONCAT(firstname, " ", lastname)'), 'like', "%$search%");
        //     })->get();
        
        // echo $scholars;

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
