<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\ScholarshipOfficer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class OfficerInfoLivewire extends Component
{
    public $user_id;
    public $password;


    protected $rules = [
        'password' => 'required|min:9',
    ];

    protected $listeners = [
        'refresh' => '$refresh',
    ];

    protected function verifyUser()
    {
        if (!Auth::check() || Auth::user()->usertype != 'admin') {
            redirect()->route('index');
            return true;
        }
        return false;
    }

    public function mount($user_id)
    {
        $this->user_id = $user_id;
    }

    public function render()
    {
        $user = User::find($this->user_id);

        return view('livewire.pages.officer.officer-info-livewire', ['user' => $user]);
    }
    
    public function updated($propertyName)
    {
        if ($this->verifyUser()) return;
        
        $user = User::find($this->user_id);
        if ( is_null($user) ) {
            $this->dispatchBrowserEvent('remove:modal-backdrop');
            return;
        }
        
        $this->validateOnly($propertyName);
    }

    public function confirm_delete()
    {
        if ( $this->verifyUser() ) return;

        if ( $this->cannotbedeleted() ) {
            return;
        }
        
        $user = User::find($this->user_id);
        if ( is_null($user) ) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'info',  
                'message' => 'Officer Account Not Found', 
                'text' => ''
            ]);
            return;
        }
        
        $this->emitTo('officer-edit-livewire', 'create');

        $confirm = $this->dispatchBrowserEvent('swal:confirm:delete_officer', [
            'type' => 'warning',  
            'message' => 'Are you sure?', 
            'text' => 'If deleted, you will not be able to recover this account!',
            'function' => "delete"
        ]);
    }

    public function delete()
    {
        if ( $this->verifyUser() ) return;

        if ( $this->cannotbedeleted() ) {
            return;
        }

        $user = User::find($this->user_id);
        if ( is_null($user) ) {
            $this->emitUp('info', null);
            return;
        }

        if (!$user->delete()) {
            return;
        }

        $this->emitUp('info', null);

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',  
            'message' => 'Officer\'s Account Deleted', 
            'text' => 'Officer\'s account has been successfully deleted'
        ]);

        $this->dispatchBrowserEvent('officer-info', ['action' => 'hide']);
    }

    protected function cannotbedeleted()
    {
        $checker = ScholarshipOfficer::select('id')
            ->where('user_id', $this->user_id)
            ->exists();

        if ($checker) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'info',  
                'message' => 'Cannot be Deleted', 
                'text' => 'Account is Connected to a Scholarship Program'
            ]);
        }
        
        return $checker;
    }

    public function change_pass()
    {
        if ($this->verifyUser()) return;
        
        $user = User::find($this->user_id);
        if ( is_null($user) ) {
            $this->dispatchBrowserEvent('remove:modal-backdrop');
            return;
        }
        
        $this->validateOnly('password');

        $user->password = Hash::make($this->password);
        $this->password = null;

        if ( $user->save() ) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',  
                'message' => 'Password Successfully Updated', 
                'text' => 'Officer\'s password has been successfully updated'
            ]);

            $this->dispatchBrowserEvent('change-password-form', ['action' => 'hide']);
            return;
        }

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'error',  
            'message' => 'Runtime error!', 
            'text' => ''
        ]);
    }
}
