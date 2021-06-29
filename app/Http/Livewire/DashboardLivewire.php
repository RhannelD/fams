<?php

namespace App\Http\Livewire;

use Livewire\Component;

class DashboardLivewire extends Component
{
    public function render()
    {
        return view('livewire.pages.dashboard.dashboard-livewire');
    }

    public function notif(){
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'error',  
            'message' => '', 
            'text' => 'Email and Password does not match'
        ]);
    }
}
