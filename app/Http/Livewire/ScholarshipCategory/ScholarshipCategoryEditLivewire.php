<?php

namespace App\Http\Livewire\ScholarshipCategory;

use Livewire\Component;
use App\Models\Scholarship;
use App\Models\ScholarshipCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ScholarshipCategoryEditLivewire extends Component
{
    use AuthorizesRequests;
    public $invalid_session = false;

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

    public function hydrate()
    {
        if ( Auth::guest() 
            || (isset($this->category_id) && Auth::user()->cannot('update', $this->get_category())) 
            || (is_null($this->category_id) && Auth::user()->cannot('create', [ScholarshipCategory::class, $this->scholarship_id]))
            ) {
            $this->invalid_session = true;
            $this->emitUp('refresh');
            $this->dispatchBrowserEvent('remove:modal-backdrop');
            $this->dispatchBrowserEvent('category-form', ['action' => 'hide']);
        } else {
            $this->invalid_session = false;
        }
    }

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

    protected function get_category()
    {
        return ScholarshipCategory::find($this->category_id);
    }
 
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        if ($this->invalid_session) return;

        $this->validate();

        $create = true;
        if ( isset($this->category_id) ) {
            $category = $this->get_category();
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
