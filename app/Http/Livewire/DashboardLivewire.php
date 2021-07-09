<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardLivewire extends Component
{
    protected $listeners = [
        'scholar_chart' => 'scholar_chart',
        'scholarship_chart' => 'scholarship_chart'
    ];

    public $scholars;

    public function render()
    {
        return view('livewire.pages.dashboard.dashboard-livewire')
            ->extends('livewire.main.main-livewire');
    }

    public function refresh_all()
    {
        $this->scholar_chart();
        $this->scholarship_chart();
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
}
