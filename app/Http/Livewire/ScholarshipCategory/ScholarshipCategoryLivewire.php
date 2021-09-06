<?php

namespace App\Http\Livewire\ScholarshipCategory;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Scholarship;
use App\Models\ScholarshipCategory;
use App\Models\ScholarshipOfficer;

class ScholarshipCategoryLivewire extends Component
{
    public $scholarship_id;
    public $delete_category_id;
    
    protected $listeners = [
        'refresh' => '$refresh',
    ];

    protected function verifyUser()
    {
        if ( !Auth::check() ) {
            redirect()->route('index');
            return true;
        }
        return false;
    }

    protected function verifyUserAccess()
    {
        if ( Auth::user()->is_admin() ) {
            return false;
        }

        $user = ScholarshipScholar::where('user_id', Auth::id())
            ->where('scholarship_id', $this->scholarship_id)
            ->first();

        if ( is_null($user) || !$user->is_admin() ) {
            redirect()->route('index');
            return true;
        }

        return false;
    }

    public function mount($scholarship_id)
    {
        $this->scholarship_id = $scholarship_id;
    }
    
    public function render()
    {
        $scholarship = Scholarship::find($this->scholarship_id);

        $categories = ScholarshipCategory::where('scholarship_id', $this->scholarship_id)->get();

        return view('livewire.pages.scholarship-category.scholarship-category-livewire', [
                'scholarship' => $scholarship,
                'categories' => $categories
            ])
            ->extends('livewire.main.main-livewire');
    }

    public function delete_category_confirmation($category_id)
    {
        if ($this->verifyUser()) return;

        $category = ScholarshipCategory::find($category_id);
        if ( is_null($category) ) {
            $this->delete_category_id = null;
            return;
        }
        $this->delete_category_id = $category_id;

        if ($this->cannotbedeleted()) return;

        $confirm = $this->dispatchBrowserEvent('swal:confirm:delete_category', [
            'type' => 'warning',  
            'message' => 'Are you sure?', 
            'text' => 'If deleted, you will not be able to recover this category!',
            'function' => "delete"
        ]);
    }

    public function delete()
    {
        if ($this->verifyUser()) return;
        
        $category = ScholarshipCategory::find($this->delete_category_id);
        if ( is_null($category) ) {
            return;
        }

        if ($this->cannotbedeleted()) return;

        if ( !$category->delete() ) {
            return;
        }
        
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
