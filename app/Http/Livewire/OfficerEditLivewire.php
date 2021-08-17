<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class OfficerEditLivewire extends Component
{
    public $user_id;
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
            'user.phone' => "required|unique:users,phone".((isset($this->user_id))?",".$this->user_id:'')."|regex:/(09)[0-9]{9}/",
            'user.birthday' => 'required|before:5 years ago',
            'user.birthplace' => 'max:200',
            'user.religion' => 'max:200',
            'user.email' => "required|unique:users,email".((isset($this->user_id))?",".$this->user_id:''),
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

    public function set_user($user_id)
    {
        if ($this->verifyUser()) return;

        $user = User::find($user_id);
        if ( is_null($user) ) {
            return;
        }

        $this->user_id            = $user_id;
        $this->user->firstname    = $user->firstname;
        $this->user->middlename   = $user->middlename;
        $this->user->lastname     = $user->lastname;
        $this->user->gender       = $user->gender;
        $this->user->birthday     = $user->birthday;
        $this->user->birthplace   = $user->birthplace;
        $this->user->religion     = $user->religion;
        $this->user->phone        = $user->phone;
        $this->user->email        = $user->email;
        $this->password           = $user->password;
        
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function unset_user()
    {
        if ($this->verifyUser()) return;

        $this->user_id = null;
        $this->user = new User;
        $this->user->gender = 'male';
        $this->password = null;
        $this->resetErrorBag();
        $this->resetValidation();
    }
    
    public function render()
    {
        return view('livewire.pages.officer.officer-edit-livewire');
    }

    public function save()
    {
        if ($this->verifyUser()) return;

        $this->validate();

        if ( isset($this->user_id) ) {
            $user = User::find($this->user_id);

            if ( is_null($user) ) {
                $this->dispatchBrowserEvent('officer-form', ['action' => 'hide']);
                $this->emitTo('officer-info-livewire', 'refresh');
                return;
            }
            $user->firstname    = $this->user->firstname;
            $user->middlename   = $this->user->middlename;
            $user->lastname     = $this->user->lastname;
            $user->gender       = $this->user->gender;
            $user->birthday     = $this->user->birthday;
            $user->birthplace   = $this->user->birthplace;
            $user->religion     = $this->user->religion;
            $user->phone        = $this->user->phone;
            $user->email        = $this->user->email;

            $this->user = $user;
        }  
        
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
