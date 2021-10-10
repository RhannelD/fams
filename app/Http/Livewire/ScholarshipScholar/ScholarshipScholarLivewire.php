<?php

namespace App\Http\Livewire\ScholarshipScholar;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ScholarshipScholar;
use Illuminate\Support\Facades\DB;
use App\Models\ScholarshipCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ScholarshipScholarLivewire extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $scholarship_id;
    public $search;
    public $show_row = 10;

    public $category_id = '';
    public $comparision = '';
    public $num_scholarship = 1;
    public $order_by = 'firstname';
    public $order = 'asc';

    protected $listeners = [
        'refresh' => '$refresh',
    ];
    
    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function hydrate()
    {
        if ( Auth::guest() || Auth::user()->is_scholar() ) {
            $this->dispatchBrowserEvent('remove:modal-backdrop');
        }
        if ( Auth::guest() || Auth::user()->cannot('viewAny', [ScholarshipScholar::class, $this->scholarship_id]) ) {
            return redirect()->route('scholarship.scholar', [$this->scholarship_id]);
        }
    }

    public function mount($scholarship_id)
    {
        $this->scholarship_id = $scholarship_id;
        $this->authorize('viewAny', [ScholarshipScholar::class, $this->scholarship_id]);
    }
    
    public function updated($name)
    {
        $this->page = 1;

        $this->valid_comparision();
        $this->valid_num_scholarship();
        $this->valid_order_by();
        $this->valid_order();
    }

    protected function valid_comparision()
    {
        if ( !in_array($this->comparision, ['', '=', '<', '>', '<=', '>=']) ) {
            $this->comparision = '';
            return false;
        }
        return $this->comparision != '';
    }

    protected function valid_num_scholarship()
    {
        if ( $this->num_scholarship < 1 || $this->num_scholarship > 20 ) {
            $this->num_scholarship = 1; 
            return false;
        }
        return true;
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

    public function render()
    {
        return view('livewire.pages.scholarship-scholar.scholarship-scholar-livewire', [
                'scholars' => $this->get_scholars(),
                'categories' => $this->get_categories(),
            ])
            ->extends('livewire.main.main-livewire');
    }

    protected function get_categories()
    {
        return ScholarshipCategory::where('scholarship_id', $this->scholarship_id)->get();
    }

    protected function get_scholars()
    {
        $search = $this->search;
        $category_id = $this->category_id;
        $comparision = $this->comparision;
        $num_scholarship = $this->num_scholarship;
        $order_by = $this->order_by;
        $order = $this->order;
        return ScholarshipScholar::whereScholarshipId($this->scholarship_id)
            ->whereHas('user', function ($query) use ($search, $comparision, $num_scholarship) {
                $query->whereNameOrEmail($search)
                    ->where('usertype', 'scholar')
                    ->when(($this->valid_comparision() && $this->valid_num_scholarship()), function ($query) use ($comparision, $num_scholarship) {
                        $query->has('scholarship_scholars', $comparision, $num_scholarship+1);
                    });
            })
            ->when(!empty($category_id), function ($query) use ($category_id) {
                $query->where('category_id', $category_id);
            })
            ->when(($this->valid_order_by() && $this->valid_order()), function ($query) use ($order_by, $order) {
                $query->join('users', 'users.id', '=', 'scholarship_scholars.user_id')
                    ->select('scholarship_scholars.*')
                    ->orderBy($order_by, $order);
            })
            ->paginate($this->show_row);
    }

    public function clear_filter()
    {
        $this->category_id = '';
        $this->comparision = '';
        $this->num_scholarship = 1;
        $this->order_by = 'firstname';
        $this->order = 'asc';
    }
}
