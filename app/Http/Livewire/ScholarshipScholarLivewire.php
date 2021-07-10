<?php

namespace App\Http\Livewire;

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
    public $categories;
    public $search;
    public $show_row = 10;
    
    public $category_id = '';

    protected function verifyUser()
    {
        if (!Auth::check()) {
            redirect()->route('dashboard');
            return true;
        }
        return false;
    }
    
    public function getQueryString()
    {
        return [];
    }
    
    public function mount($scholarship_id)
    {
        $this->scholarship_id = $scholarship_id;
    }
    
    public function updated($name)
    {
        if ('show_row') {
            $this->page = 1;
        }
    }

    public function render()
    {
        if ($this->verifyUser()) return;

        $categories = ScholarshipCategory::where('scholarship_id', $this->scholarship_id)->get();
        $this->categories = $categories->toArray();

        $search = $this->search;
        $scholars = DB::table('scholarship_scholars')->select(DB::raw('DISTINCT(scholarship_scholars.user_id)'), 'users.*', 'scholarship_categories.*')
            ->join('users', 'users.id', '=', 'scholarship_scholars.user_id')
            ->join('scholarship_categories', 'scholarship_scholars.category_id', '=', 'scholarship_categories.id')
            ->where('usertype', 'scholar')
            ->where('scholarship_categories.scholarship_id', $this->scholarship_id)
            ->where(function ($query) use ($search) {
                $query->where('firstname', 'like', "%$search%")
                    ->orWhere('middlename', 'like', "%$search%")
                    ->orWhere('lastname', 'like', "%$search%")
                    ->orWhere(DB::raw('CONCAT(firstname, " ", lastname)'), 'like', "%$search%");
            });
        if ($this->category_id != '') {
            $scholars = $scholars->where('scholarship_scholars.category_id', $this->category_id);
        }
        $scholars = $scholars
            ->paginate($this->show_row);

        return view('livewire.pages.scholarship-scholar.scholarship-scholar-livewire', ['scholars' => $scholars]);
    }
}
