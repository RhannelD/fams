<?php

namespace App\Http\Livewire\ScholarshipRequirementResponse;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\ScholarshipRequirement;

class ScholarshipRequirementResponseUnsubmittedLivewire extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $requirement_id;

    public $search   = '';
    public $show_row = 10;
    public $order_by = 'firstname';
    public $order    = 'asc';

    protected $listeners = [
        'refresh' => '$refresh',
    ];

    public function getQueryString()
    {
        return [];
    }
    
    public function mount($requirement_id)
    {
        $this->requirement_id = $requirement_id;
    }

    public function render()
    {
        return view('livewire.pages.scholarship-requirement-response.scholarship-requirement-response-unsubmitted-livewire', [
                'scholars' => $this->get_scholars_unsubmitted()
            ]);
    }

    protected function get_requirement()
    {
        return ScholarshipRequirement::find($this->requirement_id);
    }

    protected function get_scholars_unsubmitted()
    {
        $requirement = $this->get_requirement();
        if ( is_null($requirement) || is_null($requirement->categories->first()) )
            return [];

        $requirement_id = $this->requirement_id;
        $category_id = $requirement->categories->first()->category_id;
        $order_by   = $this->order_by;
        $order      = $this->order;

        return User::whereNameOrEmail($this->search)
            ->whereHas('scholarship_scholars', function ($query) use ($category_id) {
                $query->where('category_id', $category_id);
            })
            ->whereDoesntHave('responses', function ($query) use ($requirement_id) {
                $query->where('requirement_id', $requirement_id)
                    ->whereNotNull('submit_at');
            })
            ->when(($this->valid_order_by() && $this->valid_order()), function ($query) use ($order_by, $order) {
                $query->orderBy($order_by, $order);
            })
            ->paginate($this->show_row);
    }

    public function updated($name)
    {
        $this->valid_order_by();
        $this->valid_order();
    }

    protected function valid_order_by()
    {
        if ( !in_array($this->order_by, ['firstname', 'lastname', 'email', 'phone']) ) {
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
}
