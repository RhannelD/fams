<?php

namespace App\Http\Livewire\User;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class UpdatePersonalInformation extends Component
{
    public $user;

    protected $listeners = [
        'reset_values' => 'reset_values',
    ];

    public function mount()
    {
        $this->reset_values();
    }

    public function hydrate()
    {
        if ( Auth::guest() ) {
            $this->dispatchBrowserEvent('remove:modal-backdrop');
            $this->emitUp('refresh');
            return;
        }
    }

    function rules() {
        return [
            'user.phone' => "required|unique:users,phone,".Auth::id()."|regex:/(09)[0-9]\d{8}$/",
            'user.address' => 'max:200',
            'user.religion' => 'max:200',
        ];
    }

    public function render()
    {
        return view('livewire.pages.user.update-personal-information');
    }
    
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function reset_values()
    {
        if ( Auth::check() ) {
            $user = Auth::user();
            $this->user =  new User;
            $this->user->phone     = $user->phone;
            $this->user->address   = $user->address;
            $this->user->religion  = $user->religion;

        }
    }

    public function save()
    {
        if ( Auth::guest() ) 
            return;

        $this->validate();

        $user = Auth::user();
        $user->phone     = $this->user->phone;
        $user->address   = $this->user->address;
        $user->religion  = $this->user->religion;

        $user->save();
        if ( $user->wasChanged() ) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',  
                'message' => 'Personal information updated.', 
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
        $this->dispatchBrowserEvent('change-personal-info-form', ['action' => 'hide']);
        $this->emitUp('refresh');
    }
}
