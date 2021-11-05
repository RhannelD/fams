<?php

namespace App\Http\Livewire\ScholarshipOfficer;

use App\Models\User;
use Livewire\Component;
use App\Models\Scholarship;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ScholarshipOfficerLivewire extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $scholarship_id;
    public $search = '';
    public $position = '';
    public $show_row = 10;

    public $remove_officer_id;

    protected $listeners = [
        'refresh' => '$refresh',
    ];

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function hydrate()
    {
        if ( Auth::guest() ) {
            return redirect()->route('scholarship.officer', [$this->scholarship_id]);
        }
    }

    public function mount()
    {
        abort_if( Auth::guest(), '404');
    }
    
    public function render()
    {
        return view('livewire.pages.scholarship-officer.scholarship-officer-livewire', [
                'officers' => $this->get_officers()
            ])
            ->extends('livewire.main.main-livewire');
    }

    protected function get_officers()
    {
        return User::where('usertype', 'officer')
            ->whereNameOrEmail($this->search)
            ->paginate($this->show_row);
    }
    
    public function updated($name)
    {
        if ('show_row') {
            $this->page = 1;
        }
    }
}
