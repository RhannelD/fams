<?php

namespace App\Http\Livewire\ScholarshipCategory;
use Illuminate\Support\Facades\Auth;
use App\Models\Scholarship;
use App\Models\ScholarshipCategory;

use Livewire\Component;

class ScholarshipCategoryEditLivewire extends Component
{
    public $scholarship_id;
    public $category_id;
    public $category;

    protected $rules = [
        'category.category' => 'required|string|min:2|max:16000000',
        'category.amount' => 'required|numeric|min:1|max:16000000',
    ];

    protected $listeners = [
        'unset_category' => 'unset_category',
        'set_category' => 'set_category',
    ];

    public function mount($scholarship_id)
    {
        $this->scholarship_id = $scholarship_id;
        $this->unset_category();
    }

    public function unset_category()
    {
        $this->category_id = null;
        $this->category = new ScholarshipCategory;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function set_category($category_id)
    {
        $category = ScholarshipCategory::find($category_id);
        if ( is_null($category) ) {
            $this->emitUp('refresh');
            $this->dispatchBrowserEvent('category-form', ['action' => 'hide']);
            return;
        }
        $this->category_id = $category_id;
        $this->category->category = $category->category;
        $this->category->amount   = $category->amount;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.pages.scholarship-category.scholarship-category-edit-livewire');
    }
 
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        $this->validate();

        if ( is_null(Scholarship::find($this->scholarship_id)) ) {
            return;
        }

        $create = true;
        if ( isset($this->category_id) ) {
            $category = ScholarshipCategory::find($this->category_id);
            if ( is_null($category) ) {
                return;
            }
            $create = false;
            
            $category->category = $this->category->category;
            $category->amount   = $this->category->amount;
            $this->category = $category;
        } else {
            $this->category->scholarship_id = $this->scholarship_id;
        }

        $this->category->save();
        if ( $create && $this->category ) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',  
                'message' => 'Category Created', 
                'text' => 'Category has been successfully created'
            ]);
            $this->emitUp('refresh');
            $this->unset_category();
            $this->dispatchBrowserEvent('category-form', ['action' => 'hide']);

        } elseif (!$create && $this->category->wasChanged()){
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',  
                'message' => 'Category Updated', 
                'text' => 'Category has been successfully updated'
            ]);
            $this->emitUp('refresh');
            $this->unset_category();
            $this->dispatchBrowserEvent('category-form', ['action' => 'hide']);
            return;

        } elseif (!$create && !$this->category->wasChanged()){
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'info',  
                'message' => 'Nothing has been changed', 
                'text' => ''
            ]);
            return;
        }
    }
}
