<?php

namespace App\Http\Livewire\ScholarshipOfficer;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\ScholarshipOfficer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ScholarshipOfficerLivewire extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $scholarship_id;
    public $search = '';
    public $position = '';
    public $show_row = 10;

    protected $listeners = [
        'refresh' => '$refresh',
    ];

    protected function verifyUser()
    {
        if (!Auth::check()) {
            redirect()->route('index');
            return true;
        }
        return false;
    }
    
    public function mount($scholarship_id)
    {
        if ($this->verifyUser()) return;
        
        $this->scholarship_id = $scholarship_id;
    }
    
    public function updated($name)
    {
        if ($this->verifyUser()) return;

        if ('show_row') {
            $this->page = 1;
        }
    }

    public function getQueryString()
    {
        return [
            'search' => ['except' => ''],
        ];
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
        $search = $this->search;
        $position = $this->position;
        return User::where('usertype', 'officer')
            ->whereNameOrEmail($search)
            ->whereHas('scholarship_officers', function ($query) use ($position) {
                $query->where('scholarship_id', $this->scholarship_id)
                    ->when(in_array($position, ['1', '2']), function ($query) use ($position) {
                        $query->where('position_id', $position);
                    });
            })
            ->paginate($this->show_row);
    }
}
