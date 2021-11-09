<?php

namespace App\Http\Livewire\Dashboard;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\ScholarResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardLivewire extends Component
{
    protected $listeners = [
        'responses_chart' => 'responses_chart',
        'scholar_chart' => 'scholar_chart',
        'scholarship_chart' => 'scholarship_chart',
        'scholars_by_gender' => 'scholars_by_gender',
        'scholars_by_scholarship' => 'scholars_by_scholarship'
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
        return view('livewire.pages.dashboard.dashboard-livewire')
            ->extends('livewire.main.main-livewire');
    }

    public function refresh_all()
    {
        if ($this->verifyUser()) return;
        
        $this->scholar_chart();
        $this->scholarship_chart();
        $this->scholars_by_gender();
        $this->scholars_by_scholarship();
        $this->responses_chart();
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
