<?php

namespace App\Http\Livewire\ScholarshipRequirementResponse;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ScholarResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarshipRequirement;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ScholarshipRequirementResponseLivewire extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $requirement_id;
    public $index;

    public $search   = '';
    public $show_row = 10;
    public $approval = '';
    public $order_by = 'firstname';
    public $order    = 'asc';

    protected $listeners = [
        'refresh' => '$refresh',
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'index'  => ['except' => null],
    ];

    public function hydrate()
    {
        if ( Auth::guest() || Auth::user()->cannot('viewAny', [ScholarResponse::class, $this->requirement_id]) ) {
            return redirect()->route('scholarship.requirement.responses', [$this->requirement_id]);
        }
    }

    public function mount($requirement_id)
    {
        $this->requirement_id = $requirement_id;
        $this->authorize('viewAny', [ScholarResponse::class, $requirement_id]);
    }

    public function render()
    {
        return view('livewire.pages.scholarship-requirement-response.scholarship-requirement-response-livewire', [
                'requirement' => $this->get_requirement(),
                'responses'   => $this->get_responses(),
            ])
            ->extends('livewire.main.scholarship');
    }

    protected function get_requirement()
    {
        return ScholarshipRequirement::find($this->requirement_id);
    }

    protected function get_responses()
    {
        $responses  = null;
        $search     = $this->search;
        $approval   = $this->approval;
        $order_by   = $this->order_by;
        $order      = $this->order;

        $responses_temp = ScholarResponse::where('requirement_id', $this->requirement_id)
            ->whereNotNull('submit_at')
            ->whereHas('user', function ($query) use ($search) {
                $query->whereNameOrEmail($search);
            })
            ->when(!empty($approval), function ($query) use ($approval) {
                if ( $approval == '3' ) {
                    $query->whereNull('approval');
                } else {
                    $query->where('approval', ($approval == '1') );
                }
            })
            ->when(($this->valid_order_by() && $this->valid_order()), function ($query) use ($order_by, $order) {
                if ( $order_by == 'submit_at' ) {
                    $query->orderBy($order_by, $order);
                } else {
                    $query->join('users', 'users.id', '=', 'scholar_responses.user_id')
                        ->select('scholar_responses.*')
                        ->orderBy($order_by, $order);
                }
            });

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
        return $responses;
    }

    public function updated($name)
    {
        $this->page = 1;
        $this->index = null;

        $this->valid_order_by();
        $this->valid_order();
    }
    
    protected function valid_order_by()
    {
        if ( !in_array($this->order_by, ['firstname', 'lastname', 'email', 'submit_at']) ) {
            $this->order_by = 'firstname';
            return false;
        }
        return true;
    }

    protected function valid_order()
    {
        if ( !in_array($this->order, ['asc', 'desc']) ){
            $this->order = 'asc';
            return false;
        }
        return true;
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

    public function clear_filter()
    {
        $this->approval = '';
        $this->order_by = 'firstname';
        $this->order    = 'asc';
        $this->page = 1;
        $this->index = null;
    }
}
