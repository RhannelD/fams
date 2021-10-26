<?php

namespace App\Http\Livewire\User;

use Livewire\Component;
use App\Models\ScholarInfo;
use Illuminate\Support\Facades\Auth;

class UpdateFamilyInformationLivewire extends Component
{
    public $user_info;

    protected $listeners = [
        'reset_values' => 'reset_values',
    ];

    protected $rules = [
        'user_info.mother_occupation' => 'required|max:200',
        'user_info.mother_living' => 'required',
        'user_info.mother_educational_attainment' => 'required|max:200',
        'user_info.father_occupation' => 'required|max:200',
        'user_info.father_living' => 'required',
        'user_info.father_educational_attainment' => 'required|max:200',
    ];
    
    public function mount()
    {
        $this->reset_values();
    }

    public function hydrate()
    {
        if ( Auth::guest() || !Auth::user()->is_scholar() ) {
            $this->dispatchBrowserEvent('remove:modal-backdrop');
            $this->emitUp('refresh');
            return;
        }
    }

    public function render()
    {
        return view('livewire.pages.user.update-family-information-livewire');
    }
    
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function reset_values()
    {
        if ( Auth::check() ) {
            $user_info = ScholarInfo::where('user_id', Auth::id())->first();
            $this->user_info =  new ScholarInfo;
            $this->user_info->mother_occupation              = $user_info->mother_occupation;
            $this->user_info->mother_living                  = $user_info->mother_living;
            $this->user_info->mother_educational_attainment  = $user_info->mother_educational_attainment;
            $this->user_info->father_occupation              = $user_info->father_occupation;
            $this->user_info->father_living                  = $user_info->father_living;
            $this->user_info->father_educational_attainment  = $user_info->father_educational_attainment;
        }
    }

    public function save()
    {
        if ( Auth::guest() || !Auth::user()->is_scholar() ) 
            return;

        $this->validate();

        $user_info = ScholarInfo::where('user_id', Auth::id())->first();
        $user_info->mother_occupation              = $this->user_info->mother_occupation;
        $user_info->mother_living                  = $this->user_info->mother_living;
        $user_info->mother_educational_attainment  = $this->user_info->mother_educational_attainment;
        $user_info->father_occupation              = $this->user_info->father_occupation;
        $user_info->father_living                  = $this->user_info->father_living;
        $user_info->father_educational_attainment  = $this->user_info->father_educational_attainment;

        $user_info->save();
        if ( $user_info->wasChanged() ) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',  
                'message' => 'Family information updated.', 
                'text' => ''
            ]);
        } else {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'info',  
                'message' => 'Nothing has been changed', 
                'text' => ''
            ]);
        }
        
        $this->reset_values();
        $this->dispatchBrowserEvent('update-family-info-form', ['action' => 'hide']);
        $this->emitUp('refresh');
    }
}
