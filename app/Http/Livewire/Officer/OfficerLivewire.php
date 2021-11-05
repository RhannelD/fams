<?php

namespace App\Http\Livewire\Officer;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ScholarshipOfficer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OfficerLivewire extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $user;

    protected $listeners = [
        'info'    => 'info',
        'refresh' => '$refresh',
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'user'   => ['except' => null]
    ];

    public function hydrate()
    {
        if ( Auth::guest() || !Auth::user()->is_admin() ) {
            return redirect()->route('officer', ['user' => $this->user]);
        }
    }

    public function mount()
    {
        abort_if( !Auth::user()->is_admin(), '403' );
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

        $user = User::where('usertype', 'officer')
            ->where('id', $this->user)
            ->exists();
        if ( !$user ) {
            $this->user = null;
        }

        return view('livewire.pages.officer.officer-livewire', ['officers' => $officers])
            ->extends('livewire.main.main-livewire');
    }
    
    public function updated($propertyName)
    {
        if ( $propertyName == 'search' ) {
            $this->page = 0;
        }
    }

    public function info($id)
    {
        if ( Auth::guest() || !Auth::user()->is_admin() )
            return;

        if ( !isset($id) ) {
            $this->user = null;
        }

        $this->user = $id;

        $this->dispatchBrowserEvent('officer-info', ['action' => 'show']);
    } 
}
