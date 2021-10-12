<?php

namespace App\Http\Livewire\ScholarScholarship;

use Livewire\Component;
use App\Models\Scholarship;
use Illuminate\Support\Facades\Auth;

class ScholarScholarshipViewLivewire extends Component
{
    public $tab = '';

    protected $queryString = [
        'tab' => ['except' => ''],
    ];

    public function hydrate()
    {
        if ( $this->is_not_scholar() ) {
            return redirect()->route('scholar.scholarship');
        }
    }

    public function is_not_scholar()
    {
        return Auth::guest() || !Auth::user()->is_scholar();
    }

    public function mount()
    {
        if ( $this->is_not_scholar() ) abort('403', 'THIS ACTION IS UNAUTHORIZED.');
        if ( count($this->get_scholarships()) == 0 ) 
            $this->tab = 'find';
    }

    public function render()
    {
        return view('livewire.pages.scholar-scholarship.scholar-scholarship-view-livewire', [
                'scholarships' => $this->get_scholarships(),
            ])
            ->extends('livewire.main.main-livewire');
    }

    protected function get_scholarships()
    {
        return Scholarship::with(['categories' => function ($query) {
                $query->whereHas('scholars', function ($query) {
                    $query->where('user_id', Auth::id());
                });
            }])
            ->whereHas('categories', function ($query) {
                $query->whereHas('scholars', function ($query) {
                    $query->where('user_id', Auth::id());
                });
            })
            ->get();
    }
}
