<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ScholarshipRequirement;
use App\Models\ScholarResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ScholarshipRequirementResponseLivewire extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $requirement_id;

    public $search = '';
    public $approval = '';
    public $show_row = 10;

    protected $listeners = [
        'refresh' => '$refresh',
    ];

    public function mount($requirement_id)
    {
        $this->requirement_id = $requirement_id;
    }

    public function render()
    {
        $requirement = ScholarshipRequirement::find($this->requirement_id);

        $search = $this->search;
        $responses = ScholarResponse::where('requirement_id', $this->requirement_id)
            ->whereHas('user', function ($query) use ($search) {
                $query->where('firstname', 'like', "%$search%")
                    ->orWhere('middlename', 'like', "%$search%")
                    ->orWhere('lastname', 'like', "%$search%")
                    ->orWhere(DB::raw('CONCAT(firstname, " ", lastname)'), 'like', "%$search%");
            })
            ->paginate($this->show_row);

        return view('livewire.pages.scholarship-requirement-response.scholarship-requirement-response-livewire', [
                'requirement' => $requirement,
                'responses' => $responses
            ])
            ->extends('livewire.main.main-livewire');
    }
    
    public function getQueryString()
    {
        return [
            'search' => ['except' => ''],
        ];
    }
    
    public function updated($name)
    {
        if ('show_row') {
            $this->page = 1;
        }
    }
}
