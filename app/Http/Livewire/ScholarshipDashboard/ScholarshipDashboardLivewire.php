<?php

namespace App\Http\Livewire\ScholarshipDashboard;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\Scholarship;
use App\Models\ScholarResponse;
use App\Models\ScholarshipScholar;
use Illuminate\Support\Facades\DB;
use App\Models\ScholarshipCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ScholarshipDashboardLivewire extends Component
{
    use AuthorizesRequests;
    
    public $scholarship_id;

    protected $listeners = [
        'responses_chart' => 'responses_chart',
        'scholar_count_chart' => 'scholar_count_chart',
        'scholars_by_gender' => 'scholars_by_gender',
        'scholars_scholarship_count' => 'scholars_scholarship_count',
    ];

    public function mount($scholarship_id)
    {
        $this->scholarship_id = $scholarship_id;
        $this->authorize('viewDashboard', $this->get_scholarship());
    }

    public function hydrate()
    {
        if ( Auth::guest() || Auth::user()->cannot('viewDashboard', $this->get_scholarship()) ) {
            return redirect()->route('scholarship.requirement', [$this->scholarship_id]);
        }
    }

    public function render()
    {
        return view('livewire.pages.scholarship-dashboard.scholarship-dashboard-livewire')
            ->extends('livewire.main.main-livewire');
    }

    protected function get_scholarship()
    {
        return Scholarship::find($this->scholarship_id);
    }
    
    public function refresh_all()
    {
        $this->responses_chart();
        $this->scholar_count_chart();
        $this->scholars_by_gender();
        $this->scholars_scholarship_count();
    }

    public function responses_chart()
    {
        $label = [];
        $data = [];

        $date = Carbon::now();
        $categories = $this->get_scholarship()->categories;

        for ($i=0; $i < 12; $i++) { 
            $label[] = $date->format('F Y');

            $year  = $date->format('Y');
            $month = $date->format('m');
            foreach ($categories as $category) {
                $category_id = $category->id;
                $data[$category->category][] = ScholarResponse::whereNotNull('submit_at')
                    ->whereYear('submit_at', $year)
                    ->whereMonth('submit_at', $month)
                    ->whereHas('requirement', function ($query) use ($category_id) {
                        $query->where('promote', 0)
                        ->whereHas('categories', function ($query) use ($category_id) {
                            $query->where('category_id', $category_id);
                        });
                    })
                    ->count();
            }

            $date->subMonth();
        }

        $label = array_reverse($label);
        
        foreach ($data as $key => $value) {
            $data[$key] = array_reverse($value);
        }

        $this->dispatchBrowserEvent('responses_chart', [
            'label' => $label,  
            'data' => $data
        ]);
    }
    
    public function scholar_count_chart()
    {
        $scholarship_id = $this->scholarship_id;
        $categories =  ScholarshipCategory::with('scholars')
            ->where('scholarship_id', $scholarship_id)
            ->get();

        $label = [];
        $data = [];
        foreach ($categories as $category) {
            $label[] = $category->category;
            $data[]  = $category->scholars->count();
        }

        $this->dispatchBrowserEvent('scholar_count_chart', [
            'label' => $label,  
            'data' => $data
        ]);
    }

    public function scholars_by_gender()
    {
        $scholarship_id = $this->scholarship_id;
        $scholar_male =  User::where('gender', 'male')
            ->whereScholarOf($scholarship_id)
            ->count();
        $scholar_female =  User::where('gender', 'female')
            ->whereScholarOf($scholarship_id)
            ->count();

        $label = ['Male', 'Female'];
        $data = [
            $scholar_male,
            $scholar_female,
        ];

        $this->dispatchBrowserEvent('scholars_by_gender', [
            'label' => $label,  
            'data' => $data
        ]);
    }
    
    public function scholars_scholarship_count()
    {
        $scholarship_id = $this->scholarship_id;
        $scholars =  DB::select(
            "SELECT DISTINCT(COUNT(u.id)) as label, (
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
            WHERE u.id IN (
                SELECT ss3.user_id
                FROM scholarship_scholars ss3
                	INNER JOIN scholarship_categories sc3 ON ss3.category_id = sc3.id
                WHERE sc3.scholarship_id = {$scholarship_id}
            )
            GROUP BY u.id
            ORDER BY label"
        );

        $label = [];
        $data = [];
        foreach ($scholars as $scholar) {
            array_push($label, $scholar->label);
            array_push($data, $scholar->data);
        }

        $this->dispatchBrowserEvent('scholars_scholarship_count', [
            'label' => $label,  
            'data' => $data
        ]);
    }
}
