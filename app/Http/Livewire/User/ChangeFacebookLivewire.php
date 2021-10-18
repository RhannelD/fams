<?php

namespace App\Http\Livewire\User;

use Livewire\Component;
use App\Models\ScholarFacebook;
use Illuminate\Support\Facades\Auth;

class ChangeFacebookLivewire extends Component
{
    public $facebook_link;

    protected $listeners = [
        'reset_values' => 'reset_values',
    ];

    protected $rules = [
        'facebook_link' => 'required|URL',
    ];

    protected $messages = [
        'facebook_link.u_r_l' => 'Facebook Link must be a valid URL.',
    ];
    
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
        return view('livewire.pages.user.change-facebook-livewire');
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function reset_values()
    {
        $this->facebook_link = isset(Auth::user()->facebook->facebook_link)? Auth::user()->facebook->facebook_link: '';
    }

    public function save()
    {
        if ( Auth::guest() || !Auth::user()->is_scholar() ) {
            return;
        }

        $this->validate();

        ScholarFacebook::updateOrCreate(
            [
                'user_id' => Auth::id(), 
            ],
            [
                'facebook_link' => $this->facebook_link,
            ]
        );
        $this->dispatchBrowserEvent('change-facebook-form', ['action' => 'hide']);
        $this->emitUp('refresh');
    }
}
