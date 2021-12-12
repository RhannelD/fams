<?php

namespace App\Http\Livewire\Dashboard;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\Scholarship;
use Illuminate\Support\Str;
use App\Traits\YearSemTrait;
use App\Models\ScholarCourse;
use App\Models\ScholarResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarshipRequirement;

class DashboardLivewire extends Component
{
    use YearSemTrait;

    protected $listeners = [
        'responses_chart' => 'responses_chart',
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
        $acad_year = $this->get_acad_year();
        $acad_sem  = $this->get_acad_sem();

        $municipalities =  User::selectRaw("municipality, COUNT(municipality) as count")
            ->whereHas('scholarship_scholar', function ($query) use ($acad_year, $acad_sem) {
                    $query->where('acad_year', $acad_year)
                        ->where('acad_sem', $acad_sem);
                })
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
        $acad_year = $this->get_acad_year();
        $acad_sem  = $this->get_acad_sem();

        $courses =  ScholarCourse::with([
                'scholars' => function ($query) use ($acad_year, $acad_sem) {
                    $query->whereHas('user', function ($query) use ($acad_year, $acad_sem) {
                        $query->whereHas('scholarship_scholar', function ($query) use ($acad_year, $acad_sem) {
                            $query->where('acad_year', $acad_year)
                                ->where('acad_sem', $acad_sem);
                        });
                    });
                }
            ])
            ->whereHas('scholars', function ($query) use ($acad_year, $acad_sem) {
                $query->whereHas('user', function ($query) use ($acad_year, $acad_sem) {
                    $query->whereHas('scholarship_scholar', function ($query) use ($acad_year, $acad_sem) {
                        $query->where('acad_year', $acad_year)
                            ->where('acad_sem', $acad_sem);
                    });
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
    
    public function scholars_by_scholarship()
    {
        $acad_year = $this->get_acad_year();
        $acad_sem  = $this->get_acad_sem();

        $scholars =  DB::select("SELECT s.scholarship as label, COUNT(u.id) AS data
            FROM users u 
                INNER JOIN scholarship_scholars ss ON u.id = ss.user_id
                INNER JOIN scholarship_categories sc ON ss.category_id = sc.id
                INNER JOIN scholarships s ON sc.scholarship_id = s.id
            WHERE u.usertype = 'scholar'
                AND ss.acad_year = {$acad_year}
                AND ss.acad_sem = {$acad_sem}
            GROUP BY s.id
            ORDER BY data");

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
