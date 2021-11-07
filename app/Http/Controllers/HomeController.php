<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\UserChat;
use App\Models\EmailSend;
use App\Models\Scholarship;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ScholarCourse;
use App\Mail\PasswordResetMail;
use App\Models\ScholarResponse;
use App\Models\ScholarshipPost;
use App\Models\ScholarResponseGwa;
use App\Models\ScholarshipScholar;
use Illuminate\Support\Facades\DB;
use App\Mail\OfficerInvitationMail;
use App\Mail\ScholarInvitationMail;
use App\Models\ScholarResponseFile;
use App\Models\ScholarResponseUnit;
use App\Models\ScholarshipCategory;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarResponseAnswer;
use App\Models\ScholarResponseOption;
use Illuminate\Support\Facades\Route;
use App\Models\ScholarResponseComment;
use App\Models\ScholarshipPostComment;
use App\Models\ScholarshipRequirement;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Support\Facades\Storage;
use App\Models\ScholarshipScholarInvite;
use App\Mail\OfficerVerificationCodeMail;
use App\Mail\ScholarVerificationCodeMail;
use App\Models\ScholarshipRequirementItem;
use Phpml\Classification\KNearestNeighbors;
use App\Mail\UpdateEmailVerificationCodeMail;
use App\Models\ScholarshipRequirementCategory;
use App\Models\ScholarshipRequirementAgreement;
use App\Models\ScholarshipRequirementItemOption;
use App\Notifications\ScholarshipPostNotification;
use Illuminate\Notifications\Messages\MailMessage;

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
     * DB::enableQueryLog();
     * dd(DB::getQueryLog());
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // ---------------------------- BOOM ANALYTICS HAHAHAHAH ----------------------------------
        $responses = ScholarResponse::selectRaw('scholar_responses.approval, ROUND(scholar_response_gwas.gwa, 2) as gwa, scholar_response_units.units')
            ->join(with(new ScholarResponseGwa)->getTable(), 'scholar_response_gwas.response_id', '=', 'scholar_responses.id')
            ->join(with(new ScholarResponseUnit)->getTable(), 'scholar_response_units.response_id', '=', 'scholar_responses.id')
            ->whereNotNull('approval')
            ->get();

        // return $responses;

        $samples = [];
        $labels = [];
        foreach ($responses as $response) {
            $samples[] = [$response->gwa, $response->units];
            $labels[] = ($response->approval) == 1? 'approve': 'denied';
        }

        $classifier = new KNearestNeighbors();
        $classifier->train($samples, $labels);

        return $classifier->predict([1, 23]);
        // ---------------------------- BOOM ANALYTICS HAHAHAHAH END ----------------------------------

        // return 1<"0";

        // $response_id = 1;
        // return ScholarResponse::where('id', $response_id)
        //     ->with([
        //         'user', 
        //         'comments' => function ($query) {
        //             $query->with('user');
        //         },
        //         'requirement' => function ($query) use ($response_id) {
        //             $query->with([
        //                 'agreements' => function ($query) use ($response_id) {
        //                     $query->with([
        //                         'response_agreements' => function ($query) use ($response_id) {
        //                             $query->where('response_id', $response_id);
        //                         }
        //                     ]);
        //                 },
        //                 'items' => function ($query) use ($response_id) {
        //                     $query->with([
        //                         'options' => function ($query) use ($response_id) {
        //                             $query->with([
        //                                 'responses' => function ($query) use ($response_id) {
        //                                     $query->where('response_id', $response_id);
        //                                 }
        //                             ]);
        //                         },

        //                         'response_answer' => function ($query) use ($response_id) {
        //                             $query->where('response_id', $response_id);
        //                         },
        //                         'response_files' => function ($query) use ($response_id) {
        //                             $query->where('response_id', $response_id);
        //                         },
        //                     ]);
        //                 }
        //             ]);
        //         },
        //     ])
        //     ->first();

        // return '+63'.substr("09123456789", 1);

        // $category_id = 1;
        // return ScholarResponse::selectRaw('
        //         count(scholar_responses.id) as response_count, 
        //         YEAR(submit_at) AS year, 
        //         QUARTER(submit_at) AS quarter
        //     ')
        //     ->whereNotNull('submit_at')
        //     ->whereHas('requirement', function ($query) use ($category_id) {
        //         $query->where('promote', 0)
        //         ->whereHas('categories', function ($query) use ($category_id) {
        //             $query->where('category_id', $category_id);
        //         });
        //     })
        //     ->whereRaw('YEAR(submit_at) = ?', [2021])
        //     ->whereRaw('QUARTER(submit_at) = ?', [3])
        //     ->groupByRaw('year, quarter')
        //     ->orderByRaw('year DESC, quarter DESC')
        //     ->first();

        // $category_id = 1;
        // return ScholarResponse::selectRaw('count(scholar_responses.id) as response_count, YEAR(submit_at) AS year, QUARTER(submit_at) AS quarter')
        //     ->whereNotNull('submit_at')
        //     ->whereHas('requirement', function ($query) use ($category_id) {
        //         $query->where('promote', 0)
        //         ->whereHas('categories', function ($query) use ($category_id) {
        //             $query->where('category_id', $category_id);
        //         });
        //     })
        //     ->groupByRaw('year, quarter')
        //     ->orderByRaw('year DESC, quarter DESC')
        //     ->take(6)
        //     ->get();

        // $users = array(
        //     array('Firstname' => 'Alzeo','Lastname' => 'Landicho','Gender' => 'male','Email' => 'alzeo.landicho@example.com'),
        //     array('Firstname' => 'Alvin','Lastname' => 'Dalida','Gender' => 'male','Email' => 'alvin.dalida@example.com'),
        //     array('Firstname' => 'Analy','Lastname' => 'Dalida','Gender' => 'female','Email' => 'analy.dalida@example.com'),
        //     array('Firstname' => 'Jaypee','Lastname' => 'Venzon','Gender' => 'male','Email' => 'jaypee.venzon@example.com'),
        //     array('Firstname' => 'Daisy','Lastname' => 'Elposar','Gender' => 'male','Email' => 'daisy.elposar@example.com'),
        //     array('Firstname' => 'Nikki Sofia','Lastname' => 'Mailig','Gender' => 'female','Email' => 'nikkisofia.mailig@example.com'),
        //     array('Firstname' => 'Ryan','Lastname' => 'De Luna','Gender' => 'male','Email' => 'ryan.deluna@example.com'),
        //     array('Firstname' => 'Jayrald','Lastname' => 'Holgado','Gender' => 'male','Email' => 'jayrald.holgado@example.com'),
        //     array('Firstname' => 'Donnalyn','Lastname' => 'Cirilo','Gender' => 'female','Email' => 'donnalyn.cirilo@example.com'),
        //     array('Firstname' => 'Michael','Lastname' => 'Dimaala','Gender' => 'male','Email' => 'michael.dimaala@example.com'),
        //     array('Firstname' => 'Ian Glenn','Lastname' => 'Mercado','Gender' => 'male','Email' => 'ianglenn.mercado@example.com'),
        //     array('Firstname' => 'Judith','Lastname' => 'Manalo','Gender' => 'female','Email' => 'judith.manalo@example.com'),
        //     array('Firstname' => 'John Paul','Lastname' => 'Macalindong','Gender' => 'male','Email' => 'johnpaul.macalindong@example.com'),
        //     array('Firstname' => 'Ronniel','Lastname' => 'Buhay','Gender' => 'male','Email' => 'ronniel.buhay@example.com'),
        //     array('Firstname' => 'Jhanzen','Lastname' => 'Lopez','Gender' => 'male','Email' => 'jhanzen.lopez@example.com'),
        //     array('Firstname' => 'John Alex','Lastname' => 'Binuya','Gender' => 'male','Email' => 'johnalex.binuya@example.com'),
        //     array('Firstname' => 'Ma. Vanessa','Lastname' => 'Bengcang','Gender' => 'female','Email' => 'mavanessa.bengcang@example.com'),
        //     array('Firstname' => 'Angelo Kim','Lastname' => 'Espina','Gender' => 'male','Email' => 'angelokim.espina@example.com'),
        //     array('Firstname' => 'Aila Mei','Lastname' => 'Atienza','Gender' => 'female','Email' => 'ailamei.atienza@example.com'),
        //     array('Firstname' => 'Lemuel','Lastname' => 'Capacia','Gender' => 'male','Email' => 'lemuel.capacia@example.com'),
        //     array('Firstname' => 'April Rose','Lastname' => 'Catena','Gender' => 'female','Email' => 'aprilrose.catena@example.com'),
        //     array('Firstname' => 'Jean Dale','Lastname' => 'Malabanan','Gender' => 'female','Email' => 'jeandale.malabanan@example.com'),
        //     array('Firstname' => 'Nel','Lastname' => 'Tolentino','Gender' => 'male','Email' => 'nel.tolentino@example.com'),
        //     array('Firstname' => 'Brandon','Lastname' => 'Cabatian','Gender' => 'male','Email' => 'brandon.cabatin@example.com'),
        //     array('Firstname' => 'Ailyn','Lastname' => 'Data','Gender' => 'female','Email' => 'ailyn.data@example.com'),
        //     array('Firstname' => 'Dhenmark','Lastname' => 'Ignaco','Gender' => 'male','Email' => 'dhenmark.ignaco@example.com'),
        //     array('Firstname' => 'Aldwin','Lastname' => 'Zafra','Gender' => 'male','Email' => 'aldwin.zafra@example.com'),
        //     array('Firstname' => 'Weljun','Lastname' => 'De Liola','Gender' => 'male','Email' => 'weljun.deliola@example.com'),
        //     array('Firstname' => 'Noel','Lastname' => 'Carandang','Gender' => 'male','Email' => 'noel.carandang@example.com'),
        //     array('Firstname' => 'Dharwyn','Lastname' => 'Buhay','Gender' => 'male','Email' => 'dharwyn.buhay@example.com'),
        //     array('Firstname' => 'Edward','Lastname' => 'Erna','Gender' => 'male','Email' => 'edward.erna@example.com'),
        //     array('Firstname' => 'Alexandra Gheil','Lastname' => 'Emaas','Gender' => 'female','Email' => 'alexandragheil.emaas@example.com'),
        //     array('Firstname' => 'Rizzalyn','Lastname' => 'Ramirez','Gender' => 'female','Email' => 'rizzalyn.ramirez@example.com'),
        //     array('Firstname' => 'Nicole','Lastname' => 'Bascon','Gender' => 'female','Email' => 'nicole.bascon@example.com'),
        //     array('Firstname' => 'Iver','Lastname' => 'Mulingbayan','Gender' => 'female','Email' => 'iver.mulingbayan@example.com'),
        //     array('Firstname' => 'Karen','Lastname' => 'Bayaborda','Gender' => 'female','Email' => 'karen.bayaborda@example.com'),
        //     array('Firstname' => 'Aljhon','Lastname' => 'Mulingbayan','Gender' => 'male','Email' => 'aljhon.mulingbayan@example.com'),
        //     array('Firstname' => 'Francis','Lastname' => 'Andino','Gender' => 'male','Email' => 'francis.andino@example.com'),
        //     array('Firstname' => 'Jalem','Lastname' => 'Manalastas','Gender' => 'male','Email' => 'jalem.manalastas@example.com'),
        //     array('Firstname' => 'Salvador','Lastname' => 'Todoc','Gender' => 'male','Email' => 'salvador.todoc@example.com'),
        //     array('Firstname' => 'Janine','Lastname' => 'Cornejo','Gender' => 'female','Email' => 'janine.cornejo@example.com'),
        //     array('Firstname' => 'Menalyn','Lastname' => 'Mendoza','Gender' => 'female','Email' => 'menalyn.mendoza@example.com'),
        //     array('Firstname' => 'Alfred','Lastname' => 'Mendoza','Gender' => 'male','Email' => 'alfred.mendoza@example.com'),
        //     array('Firstname' => 'Kent Arvin','Lastname' => 'Berbie','Gender' => 'male','Email' => 'kentarvin.berbie@example.com'),
        //     array('Firstname' => 'John Neri','Lastname' => 'Comeso','Gender' => 'male','Email' => 'johnneri.comeso@example.com'),
        //     array('Firstname' => 'Neil','Lastname' => 'Daria','Gender' => 'male','Email' => 'neil.daria@example.com'),
        //     array('Firstname' => 'Marcel','Lastname' => 'Argente','Gender' => 'female','Email' => 'marcel.argente@example.com'),
        //     array('Firstname' => 'Alyssa','Lastname' => 'Rada','Gender' => 'female','Email' => 'alyssa.rada@example.com'),
        //     array('Firstname' => 'Orlando','Lastname' => 'Dilag','Gender' => 'male','Email' => 'orlando.dilag@example.com'),
        //     array('Firstname' => 'Melissa','Lastname' => 'Erna','Gender' => 'female','Email' => 'melissa.erna@example.com'),
        //     array('Firstname' => 'Keyzy Rose','Lastname' => 'Dime','Gender' => 'female','Email' => 'keyzyrose.dime@example.com'),
        //     array('Firstname' => 'Jessa ','Lastname' => 'Mendoza','Gender' => 'female','Email' => 'jessa.mendoza@example.com'),
        //     array('Firstname' => 'Kimberly','Lastname' => 'Oronico','Gender' => 'female','Email' => 'kimberly.oronico@example.com'),
        //     array('Firstname' => 'John Kevin','Lastname' => 'Canha','Gender' => 'male','Email' => 'johnkevin.canha@example.com'),
        //     array('Firstname' => 'Kenneth','Lastname' => 'Varias','Gender' => 'male','Email' => 'kenneth.varias@example.com'),
        //     array('Firstname' => 'Rizza','Lastname' => 'Frane','Gender' => 'female','Email' => 'rizza.frane@example.com'),
        //     array('Firstname' => 'Jessie','Lastname' => 'Andino','Gender' => 'female','Email' => 'jessie.andino@example.com'),
        //     array('Firstname' => 'Clarissa','Lastname' => 'Santos','Gender' => 'female','Email' => 'clarissa.santos@example.com'),
        //     array('Firstname' => 'Ryan','Lastname' => 'De Guzman','Gender' => 'male','Email' => 'ryan.deguzman@example.com'),
        //     array('Firstname' => 'Realyn','Lastname' => 'Mercado','Gender' => 'female','Email' => 'realyn.mercado@example.com')
        // );


        // foreach ($users as $key => $user) {
        //     echo '['.'<br>';

        //     echo "'firstname'     => '{$user['Firstname']}',".'<br>';
        //     echo "'lastname'      => '{$user['Lastname']}',".'<br>';
        //     echo "'gender'        => '{$user['Gender']}',".'<br>';
        //     echo "'email'         => '{$user['Email']}',".'<br>';

        //     echo '],'.'<br>';
        // }

        // DB::enableQueryLog();
        // $user_id = Auth::id();
        // $users = User::where('id', '!=', Auth::id())
        //     ->where(function ($query){
        //         $query->whereHas('chat_send', function ($query) {
        //             $query->where('receiver_id', Auth::id());
        //         })
        //         ->orWhereHas('chat_receive', function ($query) {
        //             $query->where('sender_id', Auth::id());
        //         });
        //     })
        //     ->select('users.*')
        //     ->addSelect(
        //         DB::raw("(
        //             select user_chats.id 
        //             from user_chats
        //             where (
        //                 sender_id = {$user_id} 
        //                 and receiver_id = users.id
        //             ) or (
        //                 receiver_id = {$user_id} 
        //                 and sender_id = users.id
        //             )
        //             ORDER BY id DESC LIMIT 1
        //         ) as last_chat_id")
        //     )
        //     ->orderBy('last_chat_id', 'desc')
        //     ->get();

        // // dd(DB::getQueryLog());
        // return $users;

        //selectRaw('YEAR(submit_at)')->
        // return Carbon::parse('2021-12-31 12:00:00');

        // $date = Carbon::now();
        // for ($i=0; $i < 10; $i++) { 
        //     echo  $date->format('F Y').'<br>';
        //     $date->subMonth();
        // }

        // $data1 = collect([
        //     '1',
        //     '2',
        //     '3',
        //     '4',
        // ]);

        // return [
        //     $data1->toJson()
        // ];

        // return Carbon::parse(ScholarshipRequirement::first()->end_at)->format('Y-m-d h:i:s') < Carbon::now()->format('Y-m-d h:i:s');

        // $req = ScholarshipRequirement::find(62);
        // return Auth::user()->can('access_on_category', $req);


        // $scholarshipPost = ScholarshipPost::find(34);
        // $user = Auth::user();
        // return $scholarshipPost->scholarship
        //     ->where(function ($query) use ($user) {
        //         $query->whereHas('officers', function ($query) use ($user) {
        //             $query->where('user_id',  $user->id);
        //         })
        //         ->orWhereHas('categories', function ($query) use ($user) {
        //             $query->whereHas('scholars', function ($query) use ($user) {
        //                 $query->where('user_id', $user->id);
        //             });
        //         });
        //     })
        //     ->exists();

        // $user = Auth::user();
        // return $user->scholarship_officers->where('scholarship_id', 2)->where('position_id', 2)->count() > 0;

        // $post = ScholarshipPost::first();
        // return $post->scholarship
        //     ->where('id', 1)
        //     ->where(function ($query) {
        //         $query->whereHas('officers', function ($query) {
        //             $query->where('user_id', 1);
        //         })
        //         ->orWhereHas('categories', function ($query) {
        //             $query->whereHas('scholars', function ($query) {
        //                 $query->where('user_id', 32);
        //             });
        //         });
        //     })
        //     ->first();

        // $post_id = 77;
        // return ScholarshipPost::where('id', $post_id)
        //     ->when(!Auth::user()->is_admin(), function ($query) {
        //         $query->whereHas('scholarship', function ($query) {
        //             $query->whereHas('officers', function ($query) {
        //                 $query->where('user_id', Auth::id());
        //             })
        //             ->orWhereHas('categories', function ($query) {
        //                 $query->whereHas('scholars', function ($query) {
        //                     $query->where('user_id', Auth::id());
        //                 });
        //             });
        //         });
        //     })
        //     ->get();

        // $scholarship_id = 1;
        // return User::where('id', Auth::id())
        //     ->where(function($query) use ($scholarship_id) {
        //         $query->whereAdmin()
        //             ->orWhere(function($query) use ($scholarship_id) {
        //                 $query->whereOfficerOf($scholarship_id);
        //             })
        //             ->orWhere(function($query) use ($scholarship_id) {
        //                 $query->whereScholarOf($scholarship_id);
        //             });
        //     })
        //     ->get();

        // $requirement = ScholarshipRequirement::get();

        // echo count($requirement);

        // echo $requirement->start_at.'<br>';
        // echo $requirement->end_at.'<br>';

        // $min = strtotime($requirement->start_at);
        // $max = strtotime($requirement->end_at);
        // $val = rand($min, $max);
        // echo date('Y-m-d H:i:s', $val);
        
    

        // return ScholarshipPost::where('scholarship_id', 1)
        // ->where('title', 'We are looking for new scholars!')
        // ->get();

        // // Random between date
        // $after  = Carbon::parse('2021-01-01 00:00:00');
        // $before = Carbon::parse('2021-06-30 23:59:59');
        // $diffInDays = $before->diffInDays($after);
        // $randomDays = rand(0, $diffInDays);
        // return $after->addDays($randomDays)->format('Y-m-d h:i:s');

        // $year_now = Carbon::now()->format('Y');
        // return $year_now < '2022';
        

        // $date = Carbon::parse('2022-01-01 00:00:00');
        // return Carbon::now()->greaterThan($date);

        // $date = strtotime('2019-01-01 00:00:01');
        // return Carbon::parse($date)->format('Y-m-d h:i:s');

        // $date = Carbon::parse('2019-01-01 00:00:00');
        // return $date->addWeeks(4)->format('Y-m-d h:i:s');
        // return $date->addMonths(6)->format('Y-m-d h:i:s');

        // ScholarCourse::factory()->create([   
        //     'course' => 'asdsdasd',
        //     'created_at' => $date,
        //     'updated_at' => $date,
        // ]);

        // return User::whereHas('scholarship_officers', function ($query) {
        //         $query->where('scholarship_id', 1);
        //     })
        //     ->get();

        // return ScholarshipPost::with('scholarship')
        //     ->wherePromote()
        //     ->whereDoesntHave('scholarship', function ($query) {
        //         $query->whereHas('categories', function ($query) {
        //             $query->whereHas('scholars', function ($query) {
        //                 $query->where('user_id', Auth::id());
        //             });
        //         });
        //     })
        //     ->orderBy('id', 'desc')
        //     ->get();

        // return ScholarSchool::orderBy('school')->get();

        // return User::with('email_update')
        //     ->take(4)
        //     ->get();

        // $comment = ScholarResponseComment::with('user')
        //     ->with(['response' => function ($query) {
        //         $query->with('comments');
        //     }])
        //     ->first();

        // // return $comment;

        // $response_id = $comment->response_id;
        // $user_id = $comment->user_id;
        // return User::whereHas('response_comments', function ($query) use ($response_id, $user_id) {
        //         $query->where('response_id', $response_id)
        //             ->where('user_id', $user_id);
        //     })
        //     ->get();

        // $details = (object) [
        //     'body_message' => 'This is the body yeahhhhh',
        //     'url' => 'https://www.google.com/',
        //     'commenter' => 'Juan dela Cruz',
        //     'comment' => 'This is the freidking comment',
        // ];

        // return (new MailMessage)->markdown('email.scholar-response-comment-notification', ['details' => $details]);

        // return route('index', ['index'=>1, 'search' => 'angel.rowe@example.net']);

        // return ScholarResponseComment::first();

        // return User::whereScholarOf(1)->take(2)->get();

        // $user = User::first();

        // $details = [
        //     'scholarship' => 'Scholarship Name',
        //     'url' => 'https://www.google.com/',
        //     'poster' => 'Juan dela Cruz',
        // ];

        // $user->notify(new ScholarshipPostNotification($details));
        
        // $details = [
        //     'email' => 'rhanneldinlasan@gmail.com',
        //     'token' => Str::random(60),
        // ];

        // return new PasswordResetMail($details);


        // if (!Auth::check()) {
        //     return abort(401);
        // }

        // return ScholarResponseFile::where('id', 7)
        //     ->when(!Auth::user()->is_admin(), function ($query) {
        //         $query->where(function ($query) {
        //             $query->whereHas('response', function ($query) {
        //                 $query->whereHas('requirement', function ($query) {
        //                     $query->whereHas('scholarship', function ($query) {
        //                         $query->whereHas('officers', function ($query) {
        //                             $query->where('user_id', 3);
        //                         });
        //                     });
        //                 });
        //             })
        //             ->orWhereHas('response', function ($query) {
        //                 $query->where('user_id', 3);
        //             });
        //         });
        //     })
        //     ->first();

        // $is_desktop = new \Jenssegers\Agent\Agent();
        // $is_desktop = $is_desktop->isDesktop();
        // if ($is_desktop) {
        //     return view('home');
        // }
        // return Storage::download('files/fakefile.pdf');

        // $response_id = 262;
        // return ScholarshipRequirement::where('id', 37)
        //     ->where(function ($query) use ($response_id) {
        //         $query->whereHas('items', function ($query) use ($response_id) {
        //                 $query->whereIn('type', ['cor', 'grade', 'file'])
        //                     ->whereDoesntHave('response_files', function ($query) use ($response_id) {
        //                         $query->where('response_id', $response_id);
        //                     });
        //             })
        //             ->orWhereHas('items', function ($query) use ($response_id) {
        //                 $query->where('type', 'question')
        //                     ->whereDoesntHave('response_answer', function ($query) use ($response_id) {
        //                         $query->where('response_id', $response_id);
        //                     });
        //             })
        //             ->orWhereHas('items', function ($query) use ($response_id) {
        //                 $query->whereIn('type', ['radio', 'check'])
        //                     ->whereDoesntHave('options', function ($query) use ($response_id) {
        //                         $query->whereHas('responses', function ($query) use ($response_id) {
        //                             $query->where('response_id', $response_id);
        //                         });
        //                     });
        //             });
        //     })
        //     ->get();

        // return ScholarshipRequirement::where('id', 37)
        //     ->with(['items' => function ($query) {
        //         $query->whereIn('type', ['radio', 'check'])
        //             ->with(['options' => function ($query) {
        //                 $query->with(['responses' => function ($query) {
        //                     $query->where('response_id', 262);
        //                 }]);
        //             }]);
        //     }])
        //     ->whereHas('items', function ($query) {
        //         $query->whereIn('type', ['radio', 'check'])
        //             ->whereDoesntHave('options', function ($query) {
        //                 $query->whereHas('responses', function ($query) {
        //                     $query->where('response_id', 262);
        //                 });
        //             });
        //     })
        //     ->get();

        // return ScholarshipRequirement::where('id', 37)
        //     ->with(['items' => function ($query) {
        //         $query->where('type', 'question')
        //             ->with('response_answer');
        //     }])
        //     ->whereHas('items', function ($query) {
        //         $query->where('type', 'question')
        //             ->whereDoesntHave('response_answer', function ($query) {
        //                 $query->where('response_id', 262);
        //             });
        //     })
        //     ->get();

        // return ScholarshipRequirement::where('id', 37)
        //     ->with(['items' => function ($query) {
        //         $query->whereIn('type', ['cor', 'grade', 'file'])->with('response_files');
        //     }])
        //     ->whereHas('items', function ($query) {
        //         $query->whereIn('type', ['cor', 'grade', 'file'])
        //             ->whereDoesntHave('response_files', function ($query) {
        //                 $query->where('response_id', 262);
        //             });
        //     })
        //     ->get();

        // return ScholarshipRequirement::with(['items' => function ($query) {
        //         $query->where('position', 3);
        //     }])
        //     ->where('id', 37)->first();

        // $search = '';
        // $position = '';
        // return User::where('usertype', 'officer')
        //     ->whereNameOrEmail($search)
        //     ->with('scholarship_officers')
        //     ->whereHas('scholarship_officers', function ($query) use ($position) {
        //         $query->where('scholarship_id', 1)
        //             ->when(in_array($position, ['1', '2']), function ($query) use ($position) {
        //                 $query->where('position_id', $position);
        //             });
        //     })
        //     ->get();


        // return ScholarshipScholar::whereScholarshipId(1)
        //     ->with('user', 'category')
        //     ->whereHas('user', function ($query) use ($search) {
        //         $query->whereNameOrEmail($search);
        //     })
        //     ->get();

        // $ScholarshipScholar = ScholarshipScholar::with('category')
        //     ->whereScholarshipId(1)->get();

        // foreach ($ScholarshipScholar as  $value) {
        //     echo $value->user->scholarship_scholars.'<br>';
        // }

        // return ScholarshipRequirement::with('agreements')
        //     ->has('agreements')
        //     ->whereHas('agreements', function ( $query) {
        //         $query->where('id', '!=', 4);
        //     })
        //     ->get();

        // return ScholarshipRequirement::find(38)->responses->count()? 'yes': 'no';

        // $input = '<h2>head1</h2><h3>head</h3><h4>head</h4><p><strong>bold</strong></p><p><i>italic</i></p><p><a href="http://localhost:8000/scholarship/requirement/6/edit"><i>http://localhost:8000/scholarship/requirement/6/edit</i></a></p><ul><li><i>1</i></li><li><i>2</i></li></ul><ol><li>3<ol><li>4</li></ol></li></ol><p>indentasdasd asdasd asdasd</p>
        // <img src="http://localhost:8000/img/scholarship-icon.png" alt="" height="80px" class="mx-auto d-block mb-2">';

        // $cleaned = Purify::clean($input);
    
        // echo $cleaned;

        // $req = ScholarshipRequirement::find(34);

        // // $req2 = $req->replicate();
        // // $req2->id = 10;
        // // return $req2;

        // foreach ($req->items as $item) {
        //     $temp = $item->replicate();
        //     return $temp;
        // }

        // DB::enableQueryLog();
        // return User::whereScholar()
        //     ->whereNotScholarOf(1)
        //     ->get();
        // dd(DB::getQueryLog());

        // return ScholarshipScholarInvite::whereScholarship(1)->get();

        // return ScholarshipOfficerInvite::where('email', 'kaylin12@example.net')->where('scholarship_id', 1)->exists();

        // return User::with('scholarship_invites')
        //     ->whereOfficer('usertype', 'officer')
        //     ->whereNotOfficerOf(1)
        //     ->doesntHave('scholarship_invites')
        //     ->whereDoesntHave('scholarship_invites', function ($query) {
        //         $query->where('scholarship_id', 1);
        //     })
        //     // ->whereHas('scholarship_invites', function($query) {
        //     //     $query->whereNotIn('scholarship_id', [1])
        //     //         ->orWhereNull('scholarship_id');
        //     // })
        //     ->get();

        // return User::with('scholarship_invites')
        //     ->leftJoin(with(new ScholarshipOfficerInvite)->getTable(),  'scholarship_officer_invites.email', '=', 'users.email')
        //     ->whereOfficer('usertype', 'officer')
        //     ->whereNotOfficerOf(1)
        //     ->whereNull('scholarship_officer_invites.email')
        //     ->get();

        // return Str::random(200);

        // $name_email = 'Celia';
        // return User::with('scholarship_officers')
        //     ->whereNameOrEmail($name_email)
        //     // ->whereNotOfficerOf(1)
        //     ->get();

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
