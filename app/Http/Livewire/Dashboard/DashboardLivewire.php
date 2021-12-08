<?php

namespace App\Http\Livewire\Dashboard;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\Scholarship;
use Illuminate\Support\Str;
use App\Models\ScholarCourse;
use App\Models\ScholarResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarshipRequirement;

class DashboardLivewire extends Component
{
    protected $listeners = [
        'responses_chart' => 'responses_chart',
        'scholar_chart' => 'scholar_chart',
        'scholarship_chart' => 'scholarship_chart',
        'scholars_by_gender' => 'scholars_by_gender',
        'scholars_by_scholarship' => 'scholars_by_scholarship',
        'scholars_by_course' => 'scholars_by_course',
        'scholars_by_municipality' => 'scholars_by_municipality',
    ];

    public $scholars;

    protected function verifyUser()
    {
        if ( Auth::guest() || !(Auth::user()->is_admin() || Auth::user()->is_officer()) ) {
            redirect()->route('index');
            return true;
        }
        return false;
    }

    public function mount()
    {
        if ($this->verifyUser()) return;
    }

    public function render()
    {
        return view('livewire.pages.dashboard.dashboard-livewire', [
                'pending_responses' => $this->get_pending_responses(),
                'ongoing_requirements' => $this->get_ongoing_requirements(),
                'pending_applications' => $this->get_pending_applications(),
                'pending_renewals' => $this->get_pending_renewals(),
                'pending_all' => $this->get_pending_all(),
                'drafts' => $this->get_drafts(),
            ])
            ->extends('livewire.main.main-livewire');
    }

    public function get_ongoing_requirements()
    {
        $datenow = Carbon::now()->format('Y-m-d h:i:s');
        return ScholarshipRequirement::where(function ($query) use ($datenow) {
                $query->where('enable', true)
                ->orWhere(function ($query) use ($datenow) {
                    $query->whereNull('enable')
                    ->where('start_at', '<=', $datenow)
                    ->where('end_at', '>=', $datenow);
                });
            })
            ->orderBy('requirement')
            ->get();
    }

    protected function get_pending_responses()
    {
        return Scholarship::with([
                'requirements' => function ($query) {
                    $query->whereHas('responses', function ($query) {
                        $query->whereNotNull('submit_at')
                            ->whereNull('approval');
                    });
                }
            ])
            ->whereHas('requirements', function ($query) {
                $query->whereHas('responses', function ($query) {
                    $query->whereNotNull('submit_at')
                        ->whereNull('approval');
                });
            })
            ->get();
    }

    protected function get_pending_applications()
    {
        return ScholarResponse::whereNull('approval')
            ->whereHas('requirement', function ($query) {
                $query->where('promote', true);
            })->count();
    }

    protected function get_pending_renewals()
    {
        return ScholarResponse::whereNull('approval')
            ->whereHas('requirement', function ($query) {
                $query->where('promote', false);
            })->count();
    }

    protected function get_pending_all()
    {
        return ScholarResponse::whereNull('approval')->count();
    }

    protected function get_drafts()
    {
        return ScholarResponse::whereNull('submit_at')->count();
    }

    public function refresh_all()
    {
        if ($this->verifyUser()) return;
        
        $this->scholar_chart();
        $this->scholarship_chart();
        $this->scholars_by_gender();
        $this->scholars_by_scholarship();
        $this->responses_chart();
        $this->scholars_by_course();
        $this->scholars_by_municipality();
    }

    public function responses_chart()
    {
        $quarters = [
            1 => 'Jan-Mar',
            2 => 'Apr-Jun',
            3 => 'Jul-Sep',
            4 => 'Oct-Dec',
        ];

        $label = [];
        $data = [];

        $date = Carbon::now();

        $iterate = 8;
        $quarter_now = $date->isoFormat('Q');
        while ( $iterate > 0 ) {
            $year  = $date->format('Y');

            for ($quarter=$quarter_now; $quarter > 0; $quarter--) { 
                if ( !$iterate ) 
                    break;

                $label[$iterate] = $year.' '.$quarters[$quarter];

                $responses = ScholarResponse::selectRaw('
                        count(scholar_responses.id) as response_count, 
                        YEAR(submit_at) AS year, 
                        QUARTER(submit_at) AS quarter
                    ')
                    ->whereNotNull('submit_at')
                    ->whereRaw('YEAR(submit_at) = ?', [$year])
                    ->whereRaw('QUARTER(submit_at) = ?', [$quarter])
                    ->groupByRaw('year, quarter')
                    ->orderByRaw('year DESC, quarter DESC')
                    ->first();

                $data[$iterate] = isset($responses)? $responses->response_count: 0;
                
                $iterate--;
            }

            $quarter_now = 4;
            $date->subYear();
        }

        $label = array_reverse($label);
        $data = array_reverse($data);

        $this->dispatchBrowserEvent('responses_chart', [
            'label' => $label,  
            'data' => $data
        ]);
    }

    public function scholars_by_municipality()
    {
        $municipalities =  User::selectRaw("municipality, COUNT(municipality) as count")
            ->has('scholarship_scholar')
            ->groupByRaw('municipality, province')
            ->get();

        $data = [];
        $label = [];

        foreach ($municipalities as $municipality) {
            $label[] = $municipality->municipality;
            $data[]  = $municipality->count;
        }

        $this->dispatchBrowserEvent('scholars_by_municipality', [
            'label' => $label,  
            'data' => $data,
        ]);
    }

    public function scholars_by_course()
    {
        $courses =  ScholarCourse::with([
                'scholars' => function ($query) {
                    $query->whereHas('user', function ($query) {
                        $query->has('scholarship_scholar');
                    });
                }
            ])
            ->whereHas('scholars', function ($query) {
                $query->whereHas('user', function ($query) {
                    $query->has('scholarship_scholar');
                });
            })
            ->get();

        $data = [];
        $label = [];

        foreach ($courses as $course) {
            $label[] = Str::limit($course->course, 50);
            $data[] = count($course->scholars);
        }

        $this->dispatchBrowserEvent('scholars_by_course', [
            'label' => $label,  
            'data' => $data
        ]);
    }
    
    public function scholar_chart()
    {
        $scholars =  DB::select('SELECT DISTINCT(COUNT(u.id)) as label, (
                SELECT COUNT(u2.id)
                FROM users u2
                WHERE (COUNT(u.id)) = (
                    SELECT COUNT(u2.id)
                    FROM scholarship_scholars ss2
                    WHERE ss2.user_id = u2.id
                    )
                ) AS data
            FROM users u
                INNER JOIN scholarship_scholars ss ON u.id = ss.user_id 
            GROUP BY u.id');

        $label = [];
        $data = [];
        foreach ($scholars as $scholar) {
            array_push($label, $scholar->label);
            array_push($data, $scholar->data);
        }

        $this->dispatchBrowserEvent('scholar_chart', [
            'label' => $label,  
            'data' => $data
        ]);
    }

    public function scholarship_chart()
    {
        $scholarships =  DB::select('SELECT DISTINCT(COUNT(sc.scholarship_id)) AS label, (
                SELECT COUNT(s.id)
                FROM scholarships s 
                WHERE COUNT(sc.scholarship_id) = (
                    SELECT COUNT(sc2.id)
                    FROM scholarship_categories sc2
                    WHERE sc2.scholarship_id = s.id
                    )
                ) AS data
            FROM scholarship_categories sc
            GROUP BY sc.scholarship_id');

        $label = [];
        $data = [];
        foreach ($scholarships as $scholarship) {
            array_push($label, $scholarship->label);
            array_push($data, $scholarship->data);
        }

        $this->dispatchBrowserEvent('scholarship_chart', [
            'label' => $label,  
            'data' => $data
        ]);
    }

    public function scholars_by_gender()
    {
        $scholars =  DB::select("SELECT COUNT(DISTINCT(u.id)) AS data, u.gender AS label
            FROM users u 
                INNER JOIN scholarship_scholars ss ON u.id = ss.user_id
            WHERE u.usertype = 'scholar'
            GROUP BY u.gender");

        $label = [];
        $data = [];
        foreach ($scholars as $scholar) {
            array_push($label, ucfirst($scholar->label));
            array_push($data, $scholar->data);
        }

        $this->dispatchBrowserEvent('scholars_by_gender', [
            'label' => $label,  
            'data' => $data
        ]);
    }

    public function scholars_by_scholarship()
    {
        $scholars =  DB::select("SELECT s.scholarship as label, COUNT(u.id) AS data
            FROM users u 
                INNER JOIN scholarship_scholars ss ON u.id = ss.user_id
                INNER JOIN scholarship_categories sc ON ss.category_id = sc.id
                INNER JOIN scholarships s ON sc.scholarship_id = s.id
            WHERE u.usertype = 'scholar'
            GROUP BY s.id");

        $label = [];
        $data = [];
        foreach ($scholars as $scholar) {
            array_push($label, $scholar->label);
            array_push($data, $scholar->data);
        }

        $this->dispatchBrowserEvent('scholars_by_scholarship', [
            'label' => $label,  
            'data' => $data
        ]);
    }
}
