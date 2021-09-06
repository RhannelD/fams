<?php

namespace App\Http\Livewire\ScholarshipScholar;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ScholarshipCategory;
use App\Models\ScholarshipScholar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ScholarshipScholarLivewire extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $scholarship_id;
    public $search;
    public $show_row = 10;

    public $comparision = '';
    public $num_scholarship = 1;
    public $category_id = '';
    public $order_by = 'firstname';
    public $order = 'asc';

    protected $listeners = [
        'refresh' => '$refresh',
    ];
    
    protected function verifyUser()
    {
        if (!Auth::check()) {
            redirect()->route('index');
            return true;
        }
        return false;
    }
    
    public function getQueryString()
    {
        return [
            'search' => ['except' => ''],
        ];
    }
    
    public function mount($scholarship_id)
    {
        if ($this->verifyUser()) return;
        
        $this->scholarship_id = $scholarship_id;
    }
    
    public function updated($name)
    {
        $this->page = 1;

        if ( !in_array($this->comparision, ['', '=', '<', '>', '<=', '>=']) ) {
            $this->comparision = '';
        }
        if ( $this->num_scholarship < 1 || $this->num_scholarship > 20 ) {
            $this->num_scholarship = 1;
        }
        if ( !in_array($this->order_by, ['firstname', 'lastname', 'email', 'phone']) ) {
            $this->order_by = 'firstname';
        }
        if ( !in_array($this->order, ['asc', 'desc']) ) {
            $this->order_by = 'asc';
        }
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
                    ->when((in_array($comparision, ['=', '<', '>', '<=', '>=']) && ($num_scholarship >= 1 || $num_scholarship <= 20) ), 
                    function ($query) use ($comparision, $num_scholarship) {
                        $query->has('scholarship_scholars', $comparision, $num_scholarship+1);
                    });
            })
            ->when(!empty($category_id), function ($query) use ($category_id) {
                $query->where('category_id', $category_id);
            })
            ->when((in_array($this->order_by, ['firstname', 'lastname', 'email', 'phone']) && in_array($order, ['asc', 'desc'])), function ($query) use ($order_by, $order) {
                $query->join('users', 'users.id', '=', 'scholarship_scholars.user_id')
                    ->select('scholarship_scholars.*')
                    ->orderBy($order_by, $order);
            })
            ->paginate($this->show_row);
    }

    public function clear_filter()
    {
        $this->comparision = '';
        $this->num_scholarship = 1;
        $this->category_id = '';
    }
}
