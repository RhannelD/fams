<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\ScholarshipScholar;
use App\Models\ScholarshipPost;
use App\Models\ScholarshipPostComment;
use App\Models\ScholarshipOfficer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class OfficerLivewire extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $user;
    public $user_id_delete;

    public $user_id;
    public $firstname;
    public $middlename;
    public $lastname;
    public $gender = 'male';
    public $phone;
    public $email;
    public $password;
    public $birthday;
    public $birthplace;
    public $religion;

    function rules() {
        return [
            'firstname' => 'required|regex:/^[a-z ,.\'-]+$/i',
            'middlename' => 'required|regex:/^[a-z ,.\'-]+$/i',
            'lastname' => 'required|regex:/^[a-z ,.\'-]+$/i',
            'gender' => 'required|in:male,female',
            'phone' => "required|unique:users".((isset($this->user_id))?",phone,$this->user_id":'')."|regex:/(09)[0-9]{9}/",
            'birthday' => 'required|before:5 years ago',
            'birthplace' => 'max:200',
            'religion' => 'max:200',
            'email' => "required|email|unique:users".((isset($this->user_id))?",email,$this->user_id":''),
            'password' => 'required|min:9',
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    protected function verifyUser()
    {
        if (!Auth::check()) {
            redirect()->route('dashboard');
            return true;
        }
        return false;
    }
    

    public function render()
    {
        $search = $this->search;

        $officers = User::where('usertype', 'officer')
            ->where(function ($query) use ($search) {
                $query->where('firstname', 'like', "%$search%")
                    ->orWhere('middlename', 'like', "%$search%")
                    ->orWhere('lastname', 'like', "%$search%")
                    ->orWhere(DB::raw('CONCAT(firstname, " ", lastname)'), 'like', "%$search%");
            })
            ->paginate(15);

        return view('livewire.pages.officer.officer-livewire', ['officers' => $officers])
            ->extends('livewire.main.main-livewire');
    }
    
    public function info($id)
    {
        if ($this->verifyUser()) return;

        $user = User::find($id);

        if (!$user) {
            $this->dispatchBrowserEvent('officer-info', ['action' => 'hide']);
            return;
        }

        $this->user = $user->toArray();

        $this->dispatchBrowserEvent('officer-info', ['action' => 'show']);
    }

    public function confirm_delete($id)
    {
        if ($this->verifyUser()) return;

        if ($this->cannotbedeleted($id)) {
            return;
        }

        $checker = ScholarshipOfficer::select('id')
            ->where('user_id', $this->user_id_delete)
            ->exists();

        if ($checker) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'info',  
                'message' => 'Cannot be Deleted', 
                'text' => 'Account is Connected to a Scholarship Program'
            ]);
            return;
        }
        
        $this->user_id_delete = $id;

        $confirm = $this->dispatchBrowserEvent('swal:confirm:delete_officer', [
            'type' => 'warning',  
            'message' => 'Are you sure?', 
            'text' => 'If deleted, you will not be able to recover this account!',
            'function' => "delete"
        ]);
    }

    public function delete()
    {
        if ($this->verifyUser()) return;

        if ($this->cannotbedeleted($this->user_id_delete)) {
            return;
        }

        ScholarshipPostComment::where('user_id', $this->user_id_delete)->delete();

        $user = User::findorfail($this->user_id_delete);
        
        if (!$user->delete()) {
            return;
        }

        $this->user = null;

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',  
            'message' => 'Scholar Account Deleted', 
            'text' => 'Scholar account has been successfully deleted'
        ]);

        $this->dispatchBrowserEvent('officer-info', ['action' => 'hide']);
    }

    protected function cannotbedeleted($id)
    {
        $checker = ScholarshipOfficer::select('id')
            ->where('user_id', $id)
            ->exists();

        if ($checker) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'info',  
                'message' => 'Cannot be Deleted', 
                'text' => 'Account is Connected to a Scholarship Program'
            ]);
            return true;
        }
        
        $checker = ScholarshipPost::select('id')
            ->where('user_id', $id)
            ->exists();

        if ($checker) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'info',  
                'message' => 'Cannot be Deleted', 
                'text' => 'Account is has post/s in a Scholarship Program'
            ]);
        }

        return $checker;
    }

    public function nullinputs()
    {
        $this->user_id      = null;
        $this->firstname    = null;
        $this->middlename   = null;
        $this->lastname     = null;
        $this->gender       = 'male';
        $this->phone        = null;
        $this->email        = null;
        $this->password     = null;
        $this->birthday     = null;
        $this->birthplace   = null;
        $this->religion     = null;
    }

    public function edit($id)
    {
        if ($this->verifyUser()) return;

        $data = User::findorfail($id);

        $this->user_id   = $data->id;
        $this->firstname    = $data->firstname;
        $this->middlename   = $data->middlename;
        $this->lastname     = $data->lastname;
        $this->gender       = $data->gender;
        $this->phone        = $data->phone;
        $this->email        = $data->email;
        $this->birthday     = $data->birthday;
        $this->birthplace   = $data->birthplace;
        $this->religion     = $data->religion;
    }

    public function save()
    {
        if ($this->verifyUser()) return;

        if (isset($this->user_id)) {
            $this->password = '123123123';
        }

        $data = $this->validate();
        $data['usertype'] = 'officer';
        if (isset($this->user_id)) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $officer = User::updateOrCreate(
            ['id' => $this->user_id],
            $data
        );

        if($officer->wasRecentlyCreated){
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',  
                'message' => 'Officer\'s Account Created', 
                'text' => 'Officer\'s account has been successfully created'
            ]);
            $this->info($officer->id);
            $this->dispatchBrowserEvent('officer-form', ['action' => 'hide']);
            return;
        } elseif (!$officer->wasRecentlyCreated && $officer->wasChanged()){
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',  
                'message' => 'Officer\'s Account Updated', 
                'text' => 'Officer\'s account has been successfully updated'
            ]);
            $this->info($this->user_id);
            $this->dispatchBrowserEvent('officer-form', ['action' => 'hide']);
            return;
        } elseif (!$officer->wasRecentlyCreated && !$officer->wasChanged()){
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'info',  
                'message' => 'Nothing has been changed', 
                'text' => ''
            ]);
            $this->edit($this->user_id);
            return;
        }
 
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'error',  
            'message' => 'Runtime error!', 
            'text' => ''
        ]);
    }
    
    public function change_pass(){
        if ($this->verifyUser()) return;
        
        $this->validateOnly('password');

        $user = User::find($this->user['id']);

        if(!$user) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',  
                'message' => 'Officer not found!', 
                'text' => ''
            ]);
            $this->password = null;
            $this->dispatchBrowserEvent('change-password-form', ['action' => 'hide']);
            return;
        }

        $user->password = Hash::make($this->password);

        if ($user->save()) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',  
                'message' => 'Password Successfully Updated', 
                'text' => 'Officer\'s password has been successfully updated'
            ]);
            $this->password = null;
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
