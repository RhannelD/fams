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
use App\Models\ScholarshipCategory;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarshipRequirement;

class DashboardLivewire extends Component
{
    use YearSemTrait;

    public $scholarship_id;

    protected $listeners = [
        'responses_chart' => 'responses_chart',
        'scholars_by_scholarship' => 'scholars_by_scholarship',
        'scholars_by_course' => 'scholars_by_course',
        'scholars_by_municipality' => 'scholars_by_municipality',
    ];
    
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
        $this->acad_year = $this->get_acad_year();
        $this->acad_sem  = $this->get_acad_sem();
    }

    public function render()
    {
        return view('livewire.pages.dashboard.dashboard-livewire', [
                'max_acad_year' => $this->get_acad_year(),
                'scholarships' => $this->get_scholarships(),
                'ongoing_requirements' => $this->get_ongoing_requirements(),
                'pending_applications' => $this->get_pending_applications(),
                'pending_renewals' => $this->get_pending_renewals(),
                'pending_all' => $this->get_pending_all(),
                'drafts' => $this->get_drafts(),
            ])
            ->extends('livewire.main.main-livewire');
    }

    public function get_scholarships()
    {
        return Scholarship::all();
    }

    public function get_ongoing_requirements()
    {
        $datenow = Carbon::now()->format('Y-m-d h:i:s');
        $scholarship_id = $this->scholarship_id;
        
        return ScholarshipRequirement::where(function ($query) use ($datenow) {
                $query->where('enable', true)
                ->orWhere(function ($query) use ($datenow) {
                    $query->whereNull('enable')
                    ->where('start_at', '<=', $datenow)
                    ->where('end_at', '>=', $datenow);
                });
            })
            ->when(isset($scholarship_id) && !empty($scholarship_id), function ($query) use ($scholarship_id) {
                $query->where('scholarship_id', $scholarship_id);
            })
            ->orderBy('requirement')
            ->get();
    }

    protected function get_pending_applications()
    {
        $scholarship_id = $this->scholarship_id;

        return ScholarResponse::whereNull('approval')
            ->whereHas('requirement', function ($query) use ($scholarship_id) {
                $query->where('promote', true)
                ->when(isset($scholarship_id) && !empty($scholarship_id), function ($query) use ($scholarship_id) {
                    $query->where('scholarship_id', $scholarship_id);
                });
            })
            ->count();
    }

    protected function get_pending_renewals()
    {
        $scholarship_id = $this->scholarship_id;

        return ScholarResponse::whereNull('approval')
            ->whereHas('requirement', function ($query) use ($scholarship_id) {
                $query->where('promote', false)
                ->when(isset($scholarship_id) && !empty($scholarship_id), function ($query) use ($scholarship_id) {
                    $query->where('scholarship_id', $scholarship_id);
                });
            })
            ->count();
    }

    protected function get_pending_all()
    {
        $scholarship_id = $this->scholarship_id;
        
        return ScholarResponse::whereNull('approval')
            ->when(isset($scholarship_id) && !empty($scholarship_id), function ($query) use ($scholarship_id) {
                $query->whereHas('requirement', function ($query) use ($scholarship_id) {
                    $query->where('scholarship_id', $scholarship_id);
                });
            })
            ->count();
    }

    protected function get_drafts()
    {
        $scholarship_id = $this->scholarship_id;
        
        return ScholarResponse::whereNull('submit_at')
            ->when(isset($scholarship_id) && !empty($scholarship_id), function ($query) use ($scholarship_id) {
                $query->whereHas('requirement', function ($query) use ($scholarship_id) {
                    $query->where('scholarship_id', $scholarship_id);
                });
            })
            ->count();
    }

    public function updated($propertyName)
    {
        $this->refresh_all();
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
        $data = [
            'approved' => [],
            'denied'   => [],
        ];

        $acad_year = $this->get_acad_year();
        $acad_sem  = $this->get_acad_sem();
        
        $scholarship_id = $this->scholarship_id;

        $iterate = 7;
        while ($iterate > 0) {
            $label[$iterate] = $acad_year.'-'.($acad_year+1).' '.($acad_sem=='1'? '1st': '2nd').' Sem';

            $responses = ScholarResponse::selectRaw('approval, COUNT(approval) as count')
                ->whereNotNull('submit_at')
                ->whereNotNull('approval')
                ->whereHas('requirement', function ($query) use ($scholarship_id, $acad_year, $acad_sem) {
                    $query->where('acad_year', $acad_year)
                        ->where('acad_sem', $acad_sem)
                        ->when(isset($scholarship_id) && !empty($scholarship_id), function ($query) use ($scholarship_id) {
                            $query->where('scholarship_id', $scholarship_id);
                        });
                })
                ->groupBy('approval')
                ->orderBy('approval', 'desc')
                ->get();

            $data['approved'][$iterate] = 0;
            $data['denied'][$iterate]   = 0;

            foreach ($responses as $response) {
                if ( $response->approval ) {
                    $data['approved'][$iterate] = $response->count;
                } else {
                    $data['denied'][$iterate] = $response->count;
                }
            }

            $acad_year = $this->get_prev_acad_year_by_year_sem($acad_year, $acad_sem);
            $acad_sem  = $this->get_prev_acad_sem_by_sem($acad_sem);

            $iterate--;
        }

        $label = array_reverse($label);
        $data['approved'] = array_reverse($data['approved']);
        $data['denied'] = array_reverse($data['denied']);

        $this->dispatchBrowserEvent('responses_chart', [
            'label' => $label,  
            'data' => $data
        ]);
    }

    public function scholars_by_municipality()
    {
        $scholarship_id = $this->scholarship_id;
        $acad_year = $this->get_acad_year();
        $acad_sem  = $this->get_acad_sem();

        $municipalities =  User::selectRaw("municipality, COUNT(municipality) as count")
            ->whereHas('scholarship_scholar', function ($query) use ($scholarship_id,$acad_year, $acad_sem) {
                    $query->where('acad_year', $acad_year)
                        ->where('acad_sem', $acad_sem)
                        ->when(isset($scholarship_id) && !empty($scholarship_id), function ($query) use ($scholarship_id) {
                            $query->whereScholarshipId($scholarship_id);
                        });
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
        $scholarship_id = $this->scholarship_id;
        $acad_year = $this->get_acad_year();
        $acad_sem  = $this->get_acad_sem();

        $scholars = ScholarshipCategory::with('scholarship')
            ->withCount([
                'scholars as data' => function ($query) use ($acad_year, $acad_sem) {
                    $query->where('acad_year', $acad_year)
                    ->where('acad_sem', $acad_sem);
                }
            ])
            ->when(isset($scholarship_id) && !empty($scholarship_id), function ($query) use ($scholarship_id) {
                $query->where('scholarship_id', $scholarship_id);
            })
            ->groupBy('scholarship_id')
            ->orderBy('data')
            ->get();

        $label = [];
        $data = [];
        foreach ($scholars as $scholar) {
            array_push($label, $scholar->scholarship->scholarship);
            array_push($data, $scholar->data);
        }

        $this->dispatchBrowserEvent('scholars_by_scholarship', [
            'label' => $label,  
            'data' => $data
        ]);
    }
}
