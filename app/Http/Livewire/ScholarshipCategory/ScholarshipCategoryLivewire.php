<?php

namespace App\Http\Livewire\ScholarshipCategory;

use Livewire\Component;
use App\Models\Scholarship;
use App\Models\ScholarshipOfficer;
use App\Models\ScholarshipCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ScholarshipCategoryLivewire extends Component
{
    use AuthorizesRequests;
    
    public $scholarship_id;
    public $delete_category_id;
    
    protected $listeners = [
        'refresh' => '$refresh',
    ];

    public function hydrate()
    {
        if ( Auth::guest() || Auth::user()->cannot('viewAny', [ScholarshipCategory::class, $this->scholarship_id]) ) {
            return redirect()->route('scholarship.category', [$this->scholarship_id]);
        }
    }

    public function mount($scholarship_id)
    {
        $this->scholarship_id = $scholarship_id;
        abort_if(!Scholarship::find($scholarship_id), '404');
        $this->authorize('viewAny', [ScholarshipCategory::class, $scholarship_id]);
    }
    
    public function render()
    {
        return view('livewire.pages.scholarship-category.scholarship-category-livewire', [
                'scholarship' => $this->get_scholarship(),
                'categories' => $this->get_categories()
            ])
            ->extends('livewire.main.scholarship');
    }

    protected function get_scholarship()
    {
        return Scholarship::find($this->scholarship_id);
    }

    protected function get_categories()
    {
        return ScholarshipCategory::where('scholarship_id', $this->scholarship_id)->get();
    }

    public function delete_category_confirmation($category_id)
    {
        $category = ScholarshipCategory::find($category_id);
        if ( is_null($category) || Auth::guest() || Auth::user()->cannot('delete', $category) ) {
            $this->delete_category_id = null;
            return;
        }
        $this->delete_category_id = $category_id;

        if ($this->cannotbedeleted()) return;

        $this->dispatchBrowserEvent('swal:confirm:delete_category', [
            'type' => 'warning',
            'message' => 'Are you sure?', 
            'text' => 'If deleted, you will not be able to recover this category!',
            'function' => "delete"
        ]);
    }

    public function delete()
    {
        $category = ScholarshipCategory::find($this->delete_category_id);
        if ( is_null($category) || Auth::guest() || Auth::user()->cannot('delete', $category) ) 
            return;

        if ($this->cannotbedeleted()) 
            return;

        if ( !$category->delete() ) 
            return;
        
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',  
            'message' => 'Scholar Account Deleted', 
            'text' => 'Scholar account has been successfully deleted'
        ]);
    }
    
    protected function cannotbedeleted()
    {
        $category = ScholarshipCategory::find($this->delete_category_id);
        
        if ( $category->scholars->count() > 0 ) {
            $this->delete_category_id = null;
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'info',  
                'message' => 'Cannot be Deleted', 
                'text' => 'Category has Scholars'
            ]);
            return true;
        }   
        return false;
    }
}
