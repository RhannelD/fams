<?php

namespace App\Http\Livewire\Scholar;

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

    protected $queryString = [
        'search' => ['except' => ''],
        'user'   => ['except' => null]
    ];

    public function hydrate()
    {
        if ( $this->is_not_admin() ) {
            return redirect()->route('scholar', ['user' => $this->user]);
        }
    }

    protected function is_not_admin()
    {
        return Auth::guest() || !Auth::user()->is_admin();
    }

    public function mount()
    {
        if ( $this->is_not_admin() ) abort('403', 'THIS ACTION IS UNAUTHORIZED.');
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

    public function updated($propertyName)
    {
        if ( $propertyName == 'search' ) {
            $this->page = 0;
        }
    }

    public function info($id)
    {
        if ($this->is_not_admin()) return;

        if ( !isset($id) ) {
            $this->user = null;
        }

        $this->user = $id;

        $this->dispatchBrowserEvent('scholar-info', ['action' => 'show']);
    }
}
