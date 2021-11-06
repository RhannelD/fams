<?php

namespace App\Http\Livewire\Officer;

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
            'user.phone' => "required|unique:users,phone".((isset($this->user_id))?",".$this->user_id:'')."|regex:/(09)[0-9]\d{8}$/",
            'user.birthday' => 'required|before:10 years ago|after:100 years ago',
            'user.birthplace' => 'max:200',
            'user.address' => 'max:200',
            'user.religion' => 'max:200',
            'user.email' => "required|unique:users,email".((isset($this->user_id))?",".$this->user_id:'')."|regex:/^[a-zA-Z0-9._%+-]+\@g.batstate-u.edu.ph$/i",
            'password' => 'required|min:9',
        ];
    }

    public function hydrate()
    {
        if ( $this->is_not_admin() ) {
            return redirect()->route('officer', ['user' => $this->user_id]);
        }
    }

    protected function is_not_admin()
    {
        return Auth::guest() || !Auth::user()->is_admin();
    }

    public function mount()
    {
        $this->user = new User;
        $this->user->gender = 'male';
    }

    public function set_user($user_id)
    {
        if ($this->is_not_admin()) return;

        $user = User::find($user_id);
        if ( is_null($user) ) {
            return;
        }

        $this->user_id            = $user_id;
        $this->user->firstname    = $user->firstname;
        $this->user->middlename   = $user->middlename;
        $this->user->lastname     = $user->lastname;
        $this->user->gender       = $user->gender;
        $this->user->address      = $user->address;
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
        if ($this->is_not_admin()) return;

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

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        if ($this->is_not_admin()) return;

        $this->validate();

        if ( isset($this->user_id) ) {
            $user = User::find($this->user_id);

            if ( is_null($user) ) {
                $this->dispatchBrowserEvent('officer-form', ['action' => 'hide']);
                $this->emitTo('officer.officer-info-livewire', 'refresh');
                return;
            }
            $user->firstname    = $this->user->firstname;
            $user->middlename   = $this->user->middlename;
            $user->lastname     = $this->user->lastname;
            $user->gender       = $this->user->gender;
            $user->address      = $this->user->address;
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
            $this->emitTo('officer.officer-info-livewire', 'refresh');
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
