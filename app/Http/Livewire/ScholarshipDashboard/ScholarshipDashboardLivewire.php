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
        $quarters = [
            1 => 'Jan-Mar',
            2 => 'Apr-Jun',
            3 => 'Jul-Sep',
            4 => 'Oct-Dec',
        ];

        $label = [];
        $data = [];

        $date = Carbon::now();
        $categories = $this->get_scholarship()->categories;

        $iterate = 6;
        $quarter_now = $date->isoFormat('Q');
        while ( true ) {
            if ( !($iterate > 0) ) 
                break;

            $year  = $date->format('Y');

            for ($quarter=$quarter_now; $quarter > 0; $quarter--) { 
                if ( !$iterate ) 
                    break;

                $label[$iterate] = $year.' '.$quarters[$quarter];

                foreach ($categories as $key => $category) {
                    $category_id = $category->id;
                    $responses = ScholarResponse::selectRaw('
                            count(scholar_responses.id) as response_count, 
                            YEAR(submit_at) AS year, 
                            QUARTER(submit_at) AS quarter
                        ')
                        ->whereNotNull('submit_at')
                        ->whereHas('requirement', function ($query) use ($category_id) {
                            $query->where('promote', 0)
                            ->whereHas('categories', function ($query) use ($category_id) {
                                $query->where('category_id', $category_id);
                            });
                        })
                        ->whereRaw('YEAR(submit_at) = ?', [$year])
                        ->whereRaw('QUARTER(submit_at) = ?', [$quarter])
                        ->groupByRaw('year, quarter')
                        ->orderByRaw('year DESC, quarter DESC')
                        ->first();

                    $data[$category->category][$iterate] = isset($responses)? $responses->response_count: 0;
                }
                
                $iterate--;
            }

            $quarter_now = 4;
            $date->subYear();
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
        $scholars = User::selectRaw('count(id) as data')
            ->withCount('scholarship_scholars as label')
            ->whereScholarOf($this->scholarship_id)
            ->groupBy('label')
            ->get();

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
