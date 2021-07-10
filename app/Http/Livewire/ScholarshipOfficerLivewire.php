<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\ScholarshipOfficer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ScholarshipOfficerLivewire extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $scholarship_id;
    public $search = '';
    public $show_row = 10;

    protected function verifyUser()
    {
        if (!Auth::check()) {
            redirect()->route('dashboard');
            return true;
        }
        return false;
    }

    
    public function mount($scholarship_id)
    {
        $this->scholarship_id = $scholarship_id;
    }
    
    public function updated($name)
    {
        if ('show_row') {
            $this->page = 1;
        }
    }

    public function getQueryString()
    {
        return [];
    }

    
    public function render()
    {
        $search = $this->search;

        $officers = User::where('usertype', 'officer')
            ->join('scholarship_officers', 'users.id', '=', 'scholarship_officers.user_id')
            ->join('officer_positions', 'scholarship_officers.position_id', '=', 'officer_positions.id')
            ->where('scholarship_id', $this->scholarship_id)
            ->where(function ($query) use ($search) {
                $query->where('firstname', 'like', "%$search%")
                    ->orWhere('middlename', 'like', "%$search%")
                    ->orWhere('lastname', 'like', "%$search%")
                    ->orWhere(DB::raw('CONCAT(firstname, " ", lastname)'), 'like', "%$search%");
            })
            ->paginate($this->show_row);

        return view('livewire.pages.scholarship-officer.scholarship-officer-livewire', ['officers' => $officers]);
    }
}
