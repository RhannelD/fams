<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\ScholarshipScholar;
use Illuminate\Support\Facades\DB;

class ScholarLivewire extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $user;
    public $user_id_delete;
    public $user_scholarships;
    public $user_scholarships_id_delete;

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


    public function render()
    {
        $search = $this->search;

        $scholars = User::where('usertype', 'scholar')
            ->where(function ($query) use ($search) {
                $query->where('firstname', 'like', "%$search%")
                    ->orWhere('middlename', 'like', "%$search%")
                    ->orWhere('lastname', 'like', "%$search%")
                    ->orWhere(DB::raw('CONCAT(firstname, " ", lastname)'), 'like', "%$search%");
            })
            ->paginate(15);

        return view('livewire.pages.scholar.scholar-livewire', ['scholars' => $scholars])
            ->extends('livewire.main.main-livewire');
    }

    public function info($id)
    {
        $user = User::find($id);

        if (!$user) {
            $this->dispatchBrowserEvent('scholar-info', ['action' => 'hide']);
            return;
        }

        $this->user = $user->toArray();

        $this->info_scholarships();

        $this->dispatchBrowserEvent('scholar-info', ['action' => 'show']);
    }

    public function info_scholarships()
    {
        $scholar_scholarships = ScholarshipScholar::select('scholarship_scholars.id', 'scholarship', 'category', 'amount', 'scholarship_scholars.created_at')
            ->join('scholarship_categories', 'scholarship_scholars.category_id', '=', 'scholarship_categories.id')
            ->join('scholarships', 'scholarships.id', '=', 'scholarship_categories.scholarship_id')
            ->where('scholarship_scholars.user_id', $this->user['id'])
            ->get()->toArray();

        $this->user_scholarships = $scholar_scholarships;
    }

    public function confirm_delete($id)
    {
        if ($this->cannotbedeleted($id)) {
            return;
        }

        $this->user_id_delete = $id;

        $confirm = $this->dispatchBrowserEvent('swal:confirm:delete_scholar', [
            'type' => 'warning',  
            'message' => 'Are you sure?', 
            'text' => 'If deleted, you will not be able to recover this account!',
            'function' => "delete"
        ]);
    }

    public function delete()
    {
        if ($this->cannotbedeleted($this->user_id_delete)) {
            return;
        }

        $user = User::find($this->user_id_delete);
        
        if (!$user->delete()) {
            return;
        }

        $this->user = null;
        $this->user_scholarships = null;

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',  
            'message' => 'Scholar Account Deleted', 
            'text' => 'Scholar account has been successfully deleted'
        ]);

        $this->dispatchBrowserEvent('scholar-info', ['action' => 'hide']);
    }

    protected function cannotbedeleted($id){
        $checker = ScholarshipScholar::select('id')
            ->where('user_id', $id)
            ->exists();

        if ($checker) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'info',  
                'message' => 'Cannot be Deleted', 
                'text' => 'Scholar has already Scholarship Program'
            ]);
        }
        return $checker;
    }

    public function confirm_delete_scholarship($id)
    {
        $this->user_scholarships_id_delete = $id;

        $confirm = $this->dispatchBrowserEvent('swal:confirm:delete_scholar', [
            'type' => 'warning',  
            'message' => 'Are you sure?', 
            'text' => 'If deleted, you will not be able to recover this account!',
            'function' => "delete_scholarship"
        ]);
    }

    public function delete_scholarship()
    {
        $scholarship = ScholarshipScholar::find($this->user_scholarships_id_delete);

        if (!$scholarship->delete()) {
            return;
        }
        
        $this->info_scholarships();

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',  
            'message' => 'Scholar\'s Scholarship Deleted', 
            'text' => 'Scholar\'s Scholarship has been successfully deleted'
        ]);
    }

    public function nullinputs()
    {
        $this->user_id   = null;
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
        $data = User::findorfail($id);

        $this->user_id      = $data->id;
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
        if (isset($this->user_id)) {
            $this->password = '123123123';
        }

        $data = $this->validate();
        $data['usertype'] = 'scholar';
        if (isset($this->user_id)) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $scholar = User::updateOrCreate(
            ['id' => $this->user_id],
            $data
        );

        if($scholar->wasRecentlyCreated){
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',  
                'message' => 'Scholar Account Created', 
                'text' => 'Scholar account has been successfully created'
            ]);
            $this->info($scholar->id);
            $this->dispatchBrowserEvent('scholar-form', ['action' => 'hide']);
            return;
        } elseif (!$scholar->wasRecentlyCreated && $scholar->wasChanged()){
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',  
                'message' => 'Scholar Account Updated', 
                'text' => 'Scholar account has been successfully updated'
            ]);
            $this->info($this->user_id);
            $this->dispatchBrowserEvent('scholar-form', ['action' => 'hide']);
            return;
        } elseif (!$scholar->wasRecentlyCreated && !$scholar->wasChanged()){
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
        $this->validateOnly('password');

        $user = User::find($this->user['id']);

        if(!$user) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',  
                'message' => 'Scholar not found!', 
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
                'text' => 'Scholar\'s password has been successfully updated'
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
