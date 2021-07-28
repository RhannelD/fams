<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class OfficerEditLivewire extends Component
{
    
    public $user;
    public $password;

    protected $listeners = [
        'edit' => 'set_user',
        'create' => 'unset_user'
    ];

    function rules() {
        return [
            'user.firstname' => 'required|regex:/^[a-z ,.\'-]+$/i',
            'user.middlename' => 'required|regex:/^[a-z ,.\'-]+$/i',
            'user.lastname' => 'required|regex:/^[a-z ,.\'-]+$/i',
            'user.gender' => 'required|in:male,female',
            'user.phone' => "required|unique:users,phone".((isset($this->user->id))?",".$this->user->id:'')."|regex:/(09)[0-9]{9}/",
            'user.birthday' => 'required|before:5 years ago',
            'user.birthplace' => 'max:200',
            'user.religion' => 'max:200',
            'user.email' => "required|unique:users,email".((isset($this->user->id))?",".$this->user->id:''),
            'password' => 'required|min:9',
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    protected function verifyUser()
    {
        if (!Auth::check() || Auth::user()->usertype != 'admin') {
            redirect()->route('index');
            return true;
        }
        return false;
    }

    
    public function mount()
    {
        $this->user = new User;
        $this->user->gender = 'male';
    }

    public function set_user(User $id)
    {
        if ($this->verifyUser()) return;

        $this->user = $id;
    }

    public function unset_user()
    {
        if ($this->verifyUser()) return;

        $this->user = new User;
        $this->user->gender = 'male';
    }
    
    public function render()
    {
        return view('livewire.pages.officer.officer-edit-livewire');
    }


    public function save()
    {
        if ($this->verifyUser()) return;

        if (isset($this->user->id)) {
            $user = User::find($this->user->id);

            $this->password = $user->password;
        }

        $this->validate();
        
        $this->user->usertype = 'officer';

        $create = false;
        if (!isset($this->user->id)) {
            $create = true;
            $this->user->password = Hash::make($this->password);
        }

        $this->user->save();
        $this->password = '';

        if( $create && $this->user){
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',  
                'message' => 'Officer\'s Account Created', 
                'text' => 'Officer\'s account has been successfully created'
            ]);
            $this->emitUp('info', $this->user->id);
            $this->unset_user();
            $this->dispatchBrowserEvent('officer-form', ['action' => 'hide']);
            return;
        } elseif (!$create && $this->user->wasChanged()){
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',  
                'message' => 'Officer\'s Account Updated', 
                'text' => 'Officer\'s account has been successfully updated'
            ]);
            $this->emitTo('officer-info-livewire', 'refresh');
            $this->unset_user();
            $this->dispatchBrowserEvent('officer-form', ['action' => 'hide']);
            return;
        } elseif (!$create && !$this->user->wasChanged()){
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'info',  
                'message' => 'Nothing has been changed', 
                'text' => ''
            ]);
            return;
        }
 
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'error',  
            'message' => 'Runtime error!', 
            'text' => ''
        ]);
    }
}
