<?php

namespace App\Http\Livewire\ScholarshipOfficer;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ScholarshipOfficer;
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
        if ( Auth::guest() || Auth::user()->cannot('viewAny', [ScholarshipOfficer::class, $this->scholarship_id]) ) {
            return redirect()->route('scholarship.officer', [$this->scholarship_id]);
        }
    }

    public function mount($scholarship_id)
    {
        $this->scholarship_id = $scholarship_id;
        $this->authorize('viewAny', [ScholarshipOfficer::class, $scholarship_id]);
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
        $scholarship_id = $this->scholarship_id;
        return User::with(['scholarship_officer' => function ($query) use ($scholarship_id) {
                $query->with('position')->where('scholarship_id', $scholarship_id);
            }])
            ->where('usertype', 'officer')
            ->whereNameOrEmail($search)
            ->whereHas('scholarship_officers', function ($query) use ($position, $scholarship_id) {
                $query->where('scholarship_id', $scholarship_id)
                    ->when(in_array($position, ['1', '2']), function ($query) use ($position) {
                        $query->where('position_id', $position);
                    });
            })
            ->paginate($this->show_row);
    }
    
    public function updated($name)
    {
        if ('show_row') {
            $this->page = 1;
        }
    }

    public function remove_officer_confirmation($remove_officer_id)
    {
        if ( Auth::check() && Auth::user()->can('delete', $this->get_officer($remove_officer_id)) ) {
            $this->remove_officer_id = $remove_officer_id;
            $this->dispatchBrowserEvent('swal:confirm:remove_officer', [
                'type' => 'warning',  
                'message' => 'Removing Officer', 
                'text' => '',
                'function' => "remove_officer"
            ]);
        } else {
            $this->remove_officer_id = null;
        }
    }

    protected function get_officer($officer_id)
    {
        return ScholarshipOfficer::find($officer_id);
    } 

    public function remove_officer()
    {
        $remove_officer = $this->get_officer($this->remove_officer_id);
        if ( Auth::check() && Auth::user()->can('delete', $remove_officer) ) {
            $name = $remove_officer->user->flname();
            if ( $remove_officer->delete() )
                session()->flash('message-success', "$name has been successfully removed.");
        }
    }
}
