<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ScholarLivewire extends Component
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
            redirect()->route('dashboard');
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

    public function updated($propertyName)
    {
        if ( $propertyName == 'search' ) {
            $this->page = 0;
        }
    }

    public function render()
    {
        $search = $this->search;

        $scholars = User::where('usertype', 'scholar')
            ->where(function ($query) use ($search) {
                $query->where('firstname', 'like', "%$search%")
                    ->orWhere('middlename', 'like', "%$search%")
                    ->orWhere('lastname', 'like', "%$search%")
                    ->orWhere(DB::raw('CONCAT(firstname, " ", lastname)'), 'like', "%$search%");
            })
            ->paginate(15);

        $user = User::where('usertype', 'scholar')
            ->where('id', $this->user)
            ->exists();
        if ( !$user ) {
            $this->user = null;
        }

        return view('livewire.pages.scholar.scholar-livewire', ['scholars' => $scholars])
            ->extends('livewire.main.main-livewire');
    }

    public function info($id)
    {
        if ($this->verifyUser()) return;

        if ( !isset($id) ) {
            $this->user = null;
        }

        $this->user = $id;

        $this->dispatchBrowserEvent('scholar-info', ['action' => 'show']);
    }
}
