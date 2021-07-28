<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\ScholarshipScholar;
use App\Models\ScholarshipPost;
use App\Models\ScholarshipPostComment;
use App\Models\ScholarshipOfficer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class OfficerLivewire extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $user;

    protected $listeners = [
        'info' => 'info',
    ];

    protected function verifyUser()
    {
        if (!Auth::check() || Auth::user()->usertype != 'admin') {
            redirect()->route('index');
            return true;
        }
        return false;
    }
    
    public function getQueryString()
    {
        return [
            'search' => ['except' => ''],
            'user' => ['except' => null]
        ];
    }

    public function mount()
    {
        if ($this->verifyUser()) return;
    }

    public function render()
    {
        $search = $this->search;

        $officers = User::where('usertype', 'officer')
            ->where(function ($query) use ($search) {
                $query->where('firstname', 'like', "%$search%")
                    ->orWhere('middlename', 'like', "%$search%")
                    ->orWhere('lastname', 'like', "%$search%")
                    ->orWhere(DB::raw('CONCAT(firstname, " ", lastname)'), 'like', "%$search%");
            })
            ->paginate(15);

        $user = User::select('id')
            ->where('usertype', 'officer')
            ->where('id', $this->user)
            ->first();
        if ( !$user ) {
            $this->user = null;
        }

        return view('livewire.pages.officer.officer-livewire', ['officers' => $officers])
            ->extends('livewire.main.main-livewire');
    }
    
    public function info($id)
    {
        if ($this->verifyUser()) return;

        if ( !isset($id) ) {
            $this->user = null;
        }

        $this->user = $id;

        $this->dispatchBrowserEvent('officer-info', ['action' => 'show']);
    } 
}
