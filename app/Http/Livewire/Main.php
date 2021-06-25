<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Main extends Component
{
    public $page;

    public function mount($page='dashboard')
    {
        $this->page = $page;
        $this->update_url();
    }

    public function render()
    {
        return view('livewire.main.main');
    }

    public function change_tab($page)
    {
        $this->page = $page;
        $this->update_url();
    }

    protected function update_url(){
        $this->emit('urlChange', '/main/'.$this->page);
    }

    public function signout(){
        Auth::logout();

        redirect()->route('login.index');
    }
}
