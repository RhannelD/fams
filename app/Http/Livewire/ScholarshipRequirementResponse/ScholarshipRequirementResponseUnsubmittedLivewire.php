<?php

namespace App\Http\Livewire\ScholarshipRequirementResponse;

use App\Models\User;
use Livewire\Component;
use App\Traits\YearSemTrait;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarshipRequirement;

class ScholarshipRequirementResponseUnsubmittedLivewire extends Component
{
    use YearSemTrait;
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

    protected $queryString = [];

    public function hydrate()
    {
        if ( Auth::guest() || Auth::user()->cannot('viewAny', [ScholarResponse::class, $this->requirement_id]) ) {
            $this->emitUp('refresh');
        }
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

        $prev_acad_year = $this->get_prev_acad_year_by_year_sem($requirement->acad_year, $requirement->acad_sem);
        $prev_acad_sem  = $this->get_prev_acad_sem_by_sem($requirement->acad_sem);

        return User::whereNameOrEmail($this->search)
            ->whereHas('scholarship_scholars', function ($query) use ($category_id, $prev_acad_year, $prev_acad_sem) {
                $query->where('category_id', $category_id)
                    ->where('acad_year', $prev_acad_year)
                    ->where('acad_sem', $prev_acad_sem);
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
