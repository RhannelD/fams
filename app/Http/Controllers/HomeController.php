<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Scholarship;
use App\Models\ScholarshipScholar;
use App\Models\ScholarshipOfficer;
use App\Models\ScholarshipRequirement;
use App\Models\ScholarshipRequirementItem;
use App\Models\ScholarshipRequirementItemOption;
use App\Models\ScholarshipRequirementCategory;
use App\Models\ScholarshipCategory;
use App\Models\ScholarshipPost;
use App\Models\ScholarshipPostComment;
use App\Models\ScholarResponse;
use App\Models\ScholarResponseOption;
use App\Models\ScholarResponseAnswer;
use App\Models\ScholarResponseFile;
use App\Models\ScholarResponseComment;
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
        $name_email = 'Celia';
        return User::with('scholarship_officers')
            ->whereNameOrEmail($name_email)
            // ->whereNotOfficerOf(1)
            ->get();

        // return ScholarResponse::find(21)->requirement->categories->first();

        // return User::find(30)->get_scholarship_scholar(1);

        // $agent = new \Jenssegers\Agent\Agent();
        // return $agent->isDesktop();
        
        // return ScholarResponse::find(1)->requirement->items[0]->response_files->where('response_id', 1)->first();

        // return is_null(ScholarshipPost::find(3002));

        // $comment = ScholarshipPostComment::select('scholarship_post_comments.*', 'users.firstname', 'users.lastname')
        //     ->leftJoin('users', 'scholarship_post_comments.user_id', '=', 'users.id')
        //     ->where('scholarship_post_comments.id', 1)
        //     ->first();

        // return is_null($comment);    

        // return ScholarshipScholar::with('categories')
        //     ->whereHas('categories', function ($query) {
        //         $query->where('scholarship_id', 1);
        //     })
        //     ->where('user_id', Auth::id())
        //     ->exists();

        // return ScholarResponseComment::latest('id')
        //     ->take(10)
        //     ->get()
        //     ->reverse()
        //     ->values();

        // DB::enableQueryLog();

        // return ScholarshipScholar::where('user_id', Auth::id())
        //     ->whereIn('category_id', function($query){
        //         $query->select('category_id')
        //         ->from(with(new ScholarshipRequirementCategory)->getTable())
        //         ->where('requirement_id', 5);
        //     })->get();

        // dd(DB::getQueryLog());


        // return ScholarshipRequirementCategory::where('requirement_id', 1)
        //     ->whereIn('scholarship_requirement_categories.category_id', function($query){
        //         $query->select('scholarship_scholars.category_id')
        //             ->from(with(new ScholarshipScholar)->getTable())
        //             ->where('scholarship_scholars.user_id', 28);
        //     })
        //     ->exists();


        // $file_uploads = ScholarshipRequirement::selectRaw('"file" as item, scholarship_requirement_items.id, scholarship_requirement_items.type')
        //     ->join(with(new ScholarshipRequirementItem)->getTable(), 'scholarship_requirements.id', '=', 'scholarship_requirement_items.requirement_id')
        //     ->join(with(new ScholarResponse)->getTable(), 'scholarship_requirements.id', '=', 'scholar_responses.requirement_id')
        //     ->leftJoin(with(new ScholarResponseFile)->getTable(), function($join)
        //     {
        //         $join->on('scholarship_requirement_items.id', '=', 'scholar_response_files.item_id');
        //         $join->on('scholarship_requirements.id', '=', 'scholar_response_files.response_id');
        //     })
        //     ->whereIn('scholarship_requirement_items.type', ['cor', 'grade', 'file'])
        //     ->where('scholarship_requirements.id', 1)
        //     ->where('scholar_responses.id', 1)
        //     ->whereNull('scholar_response_files.id');

        // $asnwer = ScholarshipRequirement::selectRaw('"answer" as item, scholarship_requirement_items.id, scholarship_requirement_items.type')
        //     ->join(with(new ScholarshipRequirementItem)->getTable(), 'scholarship_requirements.id', '=', 'scholarship_requirement_items.requirement_id')
        //     ->join(with(new ScholarResponse)->getTable(), 'scholarship_requirements.id', '=', 'scholar_responses.requirement_id')
        //     ->leftJoin(with(new ScholarResponseAnswer)->getTable(), function($join)
        //     {
        //         $join->on('scholarship_requirement_items.id', '=', 'scholar_response_answers.item_id');
        //         $join->on('scholarship_requirements.id', '=', 'scholar_response_answers.response_id');
        //     })
        //     ->whereIn('scholarship_requirement_items.type', ['question'])
        //     ->where('scholarship_requirements.id', 1)
        //     ->where('scholar_responses.id', 1)
        //     ->whereNull('scholar_response_answers.id');

        // $options = ScholarshipRequirement::selectRaw('"file" as item, scholarship_requirement_items.id, scholarship_requirement_items.type')
        //     ->join(with(new ScholarshipRequirementItem)->getTable(), 'scholarship_requirements.id', '=', 'scholarship_requirement_items.requirement_id')
        //     ->join(with(new ScholarResponse)->getTable(), 'scholarship_requirements.id', '=', 'scholar_responses.requirement_id')
        //     ->whereIn('scholarship_requirement_items.type', ['radio', 'check'])
        //     ->where('scholarship_requirements.id', 1)
        //     ->where('scholar_responses.id', 1)
        //     ->whereNotIn('scholar_responses.id', function($query){
        //         $query->select('scholar_response_options.response_id')
        //             ->from(with(new ScholarshipRequirementItemOption)->getTable())
        //             ->join(with(new ScholarResponseOption)->getTable(), 'scholarship_requirement_item_options.id', 'scholar_response_options.option_id')
        //             ->whereColumn('scholarship_requirement_item_options.item_id', 'scholarship_requirement_items.id')
        //             ->whereColumn('scholar_response_options.response_id', 'scholar_responses.id');
        //     });

        // return $options->union($file_uploads)->union($asnwer)->get();


        // $file_uploads = ScholarshipRequirementItem::selectRaw('"file" as item, scholarship_requirement_items.id, scholarship_requirement_items.type')
        //     ->join(with(new ScholarResponse)->getTable(), 'scholar_responses.id', '=', 'scholar_responses.id')
        //     ->leftJoin(with(new ScholarResponseFile)->getTable(), 'scholarship_requirement_items.id', '=', 'scholar_response_files.item_id')
        //     ->whereIn('scholarship_requirement_items.type', ['cor', 'grade', 'file'])
        //     ->where('scholarship_requirement_items.requirement_id', 1)
        //     ->whereNull('scholar_response_files.id')
        //     ->where('scholar_responses.id', 1);

        // $asnwer = ScholarshipRequirementItem::selectRaw('"answer" as item, scholarship_requirement_items.id, scholarship_requirement_items.type')
        //     ->join(with(new ScholarResponse)->getTable(), 'scholar_responses.id', '=', 'scholar_responses.id')
        //     ->leftJoin(with(new ScholarResponseAnswer)->getTable(), 'scholarship_requirement_items.id', '=', 'scholar_response_answers.item_id')
        //     ->whereIn('scholarship_requirement_items.type', ['question'])
        //     ->where('scholarship_requirement_items.requirement_id', 1)
        //     ->whereNull('scholar_response_answers.id')
        //     ->where('scholar_responses.id', 1);

        // $options = ScholarshipRequirementItem::selectRaw('"options" as item, scholarship_requirement_items.id, scholarship_requirement_items.type')
        //     ->join(with(new ScholarResponse)->getTable(), 'scholar_responses.id', '=', 'scholar_responses.id')
        //     ->where('scholarship_requirement_items.requirement_id', 1)
        //     ->whereIn('scholarship_requirement_items.type', ['radio', 'check'])
        //     ->whereNotIn('scholarship_requirement_items.id', function($query){
        //         $query->select('scholarship_requirement_item_options.item_id')
        //         ->from(with(new ScholarshipRequirementItemOption)->getTable())
        //         ->join(with(new ScholarResponseOption)->getTable(), 'scholarship_requirement_item_options.id', 'scholar_response_options.option_id')
        //         ->whereColumn('scholarship_requirement_item_options.item_id', 'scholarship_requirement_items.id');
        //     })
        //     ->where('scholar_responses.id', 1);

        // $unassigned = $options->union($file_uploads)->union($asnwer)->get();

        // return $unassigned;

        // return DB::table('scholar_response_files')
        //     ->where('file_url', 'fakefile.pdf')
        //     ->limit(1000)
        //     ->get()->count();

        // $requirements = ScholarshipRequirement::query()
        //     ->with('responses')
        //     ->with(array('items' => function($query) {
        //         $query->whereIn('type', ['cor', 'grade', 'file']);
        //     }))
        //     ->whereHas('items', function ($query) {
        //         $query->whereIn('type', ['cor', 'grade', 'file']);
        //     })
        //     ->get();
            
        // return $requirements;

        // $options = ScholarshipRequirementItemOption::where('item_id', 8)
        //     ->inRandomOrder()
        //     ->first();

        // return $options;

        // $requirements = ScholarshipRequirement::query()
        //     ->with('responses')
        //     ->with(array('items' => function($query) {
        //         $query->where('type','radio');
        //     }))
        //     ->whereHas('items', function ($query) {
        //         $query->where('type', 'radio');
        //     })
        //     ->get();

        // return $requirements;

        // $requirements = ScholarshipRequirement::query()
        //     ->with('responses')
        //     ->with(array('items' => function($query) {
        //         $query->where('type','question');
        //     }))
        //     ->whereHas('items', function ($query) {
        //         $query->where('type', 'question');
        //     })
        //     ->get();

        // return $requirements;

        // DB::enableQueryLog();

        // $file_uploads = ScholarshipRequirementItem::selectRaw('"file" as item, scholarship_requirement_items.id, scholarship_requirement_items.type')
        //     ->leftJoin(with(new ScholarResponseFile)->getTable(), 'scholarship_requirement_items.id', '=', 'scholar_response_files.item_id')
        //     ->whereIn('scholarship_requirement_items.type', ['cor', 'grade', 'file'])
        //     ->where('scholarship_requirement_items.requirement_id', 1)
        //     ->whereNull('scholar_response_files.id');

        // $asnwer = ScholarshipRequirementItem::selectRaw('"answer" as item, scholarship_requirement_items.id, scholarship_requirement_items.type')
        //     ->leftJoin(with(new ScholarResponseAnswer)->getTable(), 'scholarship_requirement_items.id', '=', 'scholar_response_answers.item_id')
        //     ->whereIn('scholarship_requirement_items.type', ['question'])
        //     ->where('scholarship_requirement_items.requirement_id', 1)
        //     ->whereNull('scholar_response_answers.id');

        // $options = ScholarshipRequirementItem::selectRaw('"options" as item, scholarship_requirement_items.id, scholarship_requirement_items.type')
        //     ->where('scholarship_requirement_items.requirement_id', 1)
        //     ->whereIn('scholarship_requirement_items.type', ['radio', 'check'])
        //     ->whereNotIn('scholarship_requirement_items.id', function($query){
        //         $query->select('scholarship_requirement_item_options.item_id')
        //         ->from(with(new ScholarshipRequirementItemOption)->getTable())
        //         ->join(with(new ScholarResponseOption)->getTable(), 'scholarship_requirement_item_options.id', 'scholar_response_options.option_id')
        //         ->whereColumn('scholarship_requirement_item_options.item_id', 'scholarship_requirement_items.id');
        //     });

        // $unassigned = $options->union($file_uploads)->union($asnwer)->get();

        // // dd(DB::getQueryLog());

        // return $unassigned;

        // $options = ScholarshipRequirementItem::selectRaw('"options" as item, scholarship_requirement_items.id, scholarship_requirement_items.type')
        //     ->addSelect(['respond' => ScholarshipRequirementItemOption::selectRaw('count(scholar_response_options.id)')
        //         ->join('scholar_response_options', 'scholar_response_options.option_id', '=', 'scholarship_requirement_item_options.id')
        //         ->whereColumn('scholarship_requirement_item_options.item_id', 'scholarship_requirement_items.id')
        //     ])->whereIn('scholarship_requirement_items.type', ['radio', 'check'])
        //     ->where('scholarship_requirement_items.requirement_id', 1)
        //     ->groupBy('scholarship_requirement_items.id')
        //     ->union($file_uploads)
        //     ->get();


        // $options = ScholarshipRequirementItem::selectRaw('"options" as item, scholarship_requirement_items.id, scholarship_requirement_items.type')
        //     ->addSelect(['respond' => ScholarResponseOption::selectRaw('scholar_response_options.id')
        //         ->whereColumn('scholar_response_options.option_id', 'scholarship_requirement_item_options.id')
        //         ->whereNotNull('scholar_response_options.id')
        //     ])
        //     ->join('scholarship_requirement_item_options', 'scholarship_requirement_items.id', '=', 'scholarship_requirement_item_options.item_id')
        //     ->whereIn('scholarship_requirement_items.type', ['radio', 'check'])
        //     ->where('scholarship_requirement_items.requirement_id', 1)
        //     ->groupBy('scholarship_requirement_items.id')
        //     ->get();

        // $options = ScholarshipRequirementItem::selectRaw('"options" as item, scholarship_requirement_items.id, scholarship_requirement_items.type')
        //     ->addSelect(['respond' => ScholarshipRequirementItemOption::selectRaw('scholarship_requirement_item_options.id')
        //         ->join('scholar_response_options', 'scholarship_requirement_item_options.id', '=', 'scholar_response_options.item_id')
        //         ->whereColumn('scholarship_requirement_item_options.item_id', 'scholarship_requirement_items.id')
        //         ->whereNotNull('scholarship_requirement_item_options.id')
        //     ])
        //     ->whereIn('scholarship_requirement_items.type', ['radio', 'check'])
        //     ->where('scholarship_requirement_items.requirement_id', 1)
        //     ->groupBy('scholarship_requirement_items.id')
        //     ->get();


        // $options = ScholarshipRequirementItem::with('options')
            // ->whereHas('options', function ($query) {
            //     return $query->;
            // })
            // ->where('requirement_id', 1)
            // ->get();

        // $options = $options->options;

        // $options = ScholarshipRequirementItemOption::with('responses')
        //     ->whereHas('item', function ($query) {
        //         return $query->where('requirement_id', 1);
        //     })
        //     ->get();

        // $options = ScholarshipRequirementItem::with('requirement')
        //     ->with('options')
        //     ->whereHas('options', function ($query) {
        //         return $query->with('responses');
        //     })
        //     ->where('requirement_id', 1)
        //     ->whereIn('scholarship_requirement_items.type', ['radio', 'check'])
        //     ->get();

        // return $options;


        // $first = DB::table('users')
        //     ->whereNull('first_name');

        // $users = DB::table('users')
        //     ->whereNull('last_name')
        //     ->union($first)
        //     ->get();


        // $response = ScholarResponse::firstOrCreate([
        //     'user_id' => Auth::id(),
        //     'requirement_id' => 2,
        // ]);

        // return $response;

        // $posts = ScholarshipPost::select('scholarship_posts.*', 'users.firstname', 'users.lastname')
        //     ->addSelect(['comment_count' => ScholarshipPostComment::select(DB::raw("count(scholarship_post_comments.id)"))
        //         ->whereColumn('scholarship_post_comments.post_id', 'scholarship_posts.id')
        //     ])
        //     ->where('scholarship_id', 1)
        //     ->leftJoin('users', 'scholarship_posts.user_id', '=', 'users.id')
        //     ->orderBy('id', 'desc')
        //     ->get();

        // return $posts;

        // $posts = ScholarshipPost::select('scholarship_posts.*', 'users.firstname', 'users.lastname', 
        //     DB::raw('(
        //         select count(scholarship_post_comments.id) from `scholarship_post_comments` 
        //         where `scholarship_post_comments`.`post_id` = `scholarship_posts`.`id`
        //     ) as counts'))
        //     ->leftJoin('users', 'scholarship_posts.user_id', '=', 'users.id')
        //     ->where('scholarship_posts.scholarship_id', '1')
        //     ->orderBy('id', 'desc')
        //     ->get();

        // return $posts;

        // $officers = User::select('users.*')
        //     ->join('scholarship_officers', 'users.id', '=', 'scholarship_officers.user_id')
        //     ->where('scholarship_officers.scholarship_id', 1);

        // $users = User::select('users.*')
        //     ->join('scholarship_scholars', 'users.id', '=', 'scholarship_scholars.user_id')
        //     ->join('scholarship_categories', 'scholarship_scholars.category_id', '=', 'scholarship_categories.id')
        //     ->where('scholarship_categories.scholarship_id', 1)
        //     ->union($officers)
        //     ->get();

        // return $users;

        // $categories = ScholarshipCategory::select('*')
        // ->join('scholarship_requirements', 'scholarship_categories.scholarship_id', '=', 'scholarship_requirements.scholarship_id')
        // ->leftJoin('scholarship_requirement_categories', function($join) {
        //         $join->on('scholarship_categories.id', '=', 'scholarship_requirement_categories.category_id');
        //         $join->on('scholarship_requirements.id', '=', 'scholarship_requirement_categories.requirement_id');
        //     })
        // ->where('scholarship_requirements.id', 1)
        // ->get();

        // return $categories;
        // echo Carbon::now()->addDay()->format('Y-m-d H:i:s').'<br>';
        // return Carbon::now()->format('Y-m-d H:i:s');

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
