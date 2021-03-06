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
use App\Models\ScholarshipScholar;
use Illuminate\Support\Facades\DB;
use App\Models\ScholarshipCategory;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarshipRequirement;

class DashboardLivewire extends Component
{
    use YearSemTrait;

    public $scholarship_id;

    public $filter_line_year = true;
    public $filter_span = 5;

    public $colors = [
        "#00aba9",
        "#b91d47",
        '#494949',
        '#496076',
        '#AE557F',
        '#132664',
        '#32A350',
        '#492B4C',
        '#F49E12',
        '#C2F1DB',
    ];

    protected $listeners = [
        'scholarship_scholars_trend' => 'scholarship_scholars_trend',
        'response_approve_denied' => 'response_approve_denied',
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
        
        $this->scholarship_scholars_trend();
        $this->response_approve_denied();
        $this->scholars_by_municipality();
    }

    public function get_scholarship()
    {
        return isset($this->scholarship_id)? Scholarship::find($this->scholarship_id): null;
    }

    public function scholarship_scholars_trend()
    {
        $label = [];
        $data = [];

        $acad_year = $this->get_acad_year();
        $acad_sem  = $this->get_acad_sem();
        
        $scholarship_id = $this->scholarship_id;
        $scholarship = $this->get_scholarship();

        $colors = $this->colors;
        $color_count = count($this->colors);

        $iterate = $this->filter_span;
        while ($iterate > 0) {
            if ( $this->filter_line_year ) {
                $label[$iterate] = $acad_year.'-'.($acad_year+1);
            } else {
                $label[$iterate] = $acad_year.'-'.($acad_year+1).' '.($acad_sem=='1'? '1st': '2nd').' Sem';
            }
            
            $scholar_count = ScholarshipScholar::selectRaw('COUNT(DISTINCT(user_id)) as data')
                ->where('acad_year', $acad_year)
                ->when(!$this->filter_line_year, function ($query) use ($acad_sem) {
                        $query->where('acad_sem', $acad_sem);
                    })
                ->when(!empty($scholarship_id), function ($query) use ($scholarship_id) {
                        $query->whereHas('category', function ($query) use ($scholarship_id) {
                            $query->where('scholarship_id', $scholarship_id);
                        });
                    })
                ->first();

            $data[0]['counts'][$iterate] = $scholar_count->data;
            $data[0]['label'] = $scholarship? $scholarship->scholarship: null;
            $data[0]['color'] = '#61C97D';

            if ( $this->filter_line_year ) {
                $acad_year -= 1;
            } else {
                $acad_year = $this->get_prev_acad_year_by_year_sem($acad_year, $acad_sem);
                $acad_sem  = $this->get_prev_acad_sem_by_sem($acad_sem);    
            }

            $iterate--;
        }

        $label = array_reverse($label);
        foreach ($data as $key => $value) {
            $data[$key]['counts'] = array_reverse($value['counts']);
        }

        $this->dispatchBrowserEvent('scholarship_scholars_trend', [
            'label' => $label,  
            'data' => $data,
            'title' => 'Count of Scholars '.($this->filter_line_year? 'Annually': ' each Semester'),
        ]);
    }

    public function scholars_by_municipality()
    {
        $acad_year = $this->get_acad_year();
        $acad_sem  = $this->get_acad_sem();

        $colors      = $this->colors;
        $color_count = count($this->colors);
        
        $data  = [];
        $label = [];

        $municipalities = User::selectRaw('municipality, province')->groupByRaw('municipality, province')->orderByRaw('municipality, province')->get();
        foreach ($municipalities as $key_municipality => $municipality) {
            $label[$key_municipality] = $municipality->municipality;
        }

        $scholarship_id = $this->scholarship_id;
        $scholarship = $this->get_scholarship();
        
        $data[0] = [];
        $data[0]['data'] = [];

        $scholars = User::selectRaw('municipality, province, COUNT(users.id) as data')
            ->whereHas('scholarship_scholars', function ($query) use ($scholarship_id, $acad_year, $acad_sem) {
                $query->whereYearSem($acad_year, $acad_sem)
                    ->whereHas('category', function ($query) use ($scholarship_id) {
                        $query->when(!empty($scholarship_id), function ($query) use ($scholarship_id) {
                            $query->where('scholarship_id', '=', $scholarship_id);
                        });
                    });
            })
            ->groupByRaw('municipality, province')
            ->get();

        foreach ($municipalities as $key_municipality => $municipality) {
            foreach ($scholars as $key_scholar => $scholar) {
                if ( $municipality->municipality == $scholar->municipality && $municipality->province == $scholar->province ) {
                    $data[0]['label'] = $scholarship? $scholarship->scholarship: null;
                    $data[0]['color'] = '#61C97D';
                    $data[0]['data'][$key_municipality] = $scholar->data;
                }
            }
        }

        $this->dispatchBrowserEvent('scholars_by_municipality', [
            'label' => $label,  
            'data' => $data,
        ]);
    }

    public function response_approve_denied()
    {
        $label = [];
        $data = [];

        $acad_year = $this->get_acad_year();
        $acad_sem  = $this->get_acad_sem();
        
        $scholarship_id = $this->scholarship_id;

        $colors = $this->colors;
        $color_count = count($this->colors);

        $filter_line_year = $this->filter_line_year;
        $iterate = $this->filter_span;
        while ($iterate > 0) {
            if ( $filter_line_year ) {
                $label[$iterate] = $acad_year.'-'.($acad_year+1);
            } else {
                $label[$iterate] = $acad_year.'-'.($acad_year+1).' '.($acad_sem=='1'? '1st': '2nd').' Sem';
            }

            $response = ScholarResponse::selectRaw('COUNT(id) as data, approval')
                ->whereHas('requirement', function ($query) use ($scholarship_id, $acad_year, $acad_sem, $filter_line_year) {
                    $query->where('acad_year', $acad_year)
                        ->when(!$filter_line_year, function ($query) use ($acad_sem) {
                            $query->where('acad_sem', $acad_sem);
                        })
                        ->when(!empty($scholarship_id), function ($query) use ($scholarship_id) {
                            $query->where('scholarship_id', $scholarship_id);
                        });
                })
                ->where('approval', true)
                ->count();

            $data['approved']['counts'][$iterate] = $response;
            $data['approved']['color'] = $colors[1];

            $response = ScholarResponse::selectRaw('COUNT(id) as data, approval')
                ->whereHas('requirement', function ($query) use ($scholarship_id, $acad_year, $acad_sem, $filter_line_year) {
                    $query->where('acad_year', $acad_year)
                        ->when(!$filter_line_year, function ($query) use ($acad_sem) {
                            $query->where('acad_sem', $acad_sem);
                        })
                        ->when(!empty($scholarship_id), function ($query) use ($scholarship_id) {
                            $query->where('scholarship_id', $scholarship_id);
                        });
                })
                ->where('approval', false)
                ->count();
            
            $data['denied']['counts'][$iterate] = $response;
            $data['denied']['color'] = $colors[2];

            if ( $filter_line_year ) {
                $acad_year -= 1;
            } else {
                $acad_year = $this->get_prev_acad_year_by_year_sem($acad_year, $acad_sem);
                $acad_sem  = $this->get_prev_acad_sem_by_sem($acad_sem);    
            }

            $iterate--;
        }

        $label = array_reverse($label);
        foreach ($data as $key => $value) {
            $data[$key]['counts'] = array_reverse($value['counts']);
        }

        $this->dispatchBrowserEvent('response_approve_denied', [
            'label' => $label,  
            'data' => $data,
            'title' => 'Count of Approved and Denied Applications/Renewals '.($this->filter_line_year? 'Annually': ' each Semester'),
        ]);
    }
}
