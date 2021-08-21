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
    public $index;

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
        $responses = null;
        $responses_temp = ScholarResponse::where('requirement_id', $this->requirement_id)
            ->whereHas('user', function ($query) use ($search) {
                $query->where('firstname', 'like', "%$search%")
                    ->orWhere('middlename', 'like', "%$search%")
                    ->orWhere('lastname', 'like', "%$search%")
                    ->orWhere(DB::raw('CONCAT(firstname, " ", lastname)'), 'like', "%$search%");
            });
            
        if ( !empty($this->approval) ) {
            if ( $this->approval == '3' ) {
                $responses_temp = $responses_temp->whereNull('approval');
            } else {
                $responses_temp = $responses_temp->where('approval', ($this->approval == '1') );
            }
        }

        if ( isset($this->index) ) {
            $temp = $responses_temp->get();
            if ( ($temp->count() - 1) < $this->index || is_null($temp[$this->index]) ) {
                $this->index = null;
            } else {
                $responses = $temp;
            }
        }

        if ( is_null($this->index) ) {
            $responses = $responses_temp->paginate($this->show_row);
        }

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
            'index' => ['except' => null]
        ];
    }
    
    public function updated($name)
    {
        if ('show_row') {
            $this->page = 1;
        }
    }

    public function view_response($index)
    {
        $this->index = ($this->show_row * ($this->page - 1)) + $index;
    }

    public function unview_response()
    {
        $this->index = null;
    }
    
    public function change_index($num)
    {
        if ( isset($this->index) ) {
            $this->index = $this->index + $num;
        }
    }
}
