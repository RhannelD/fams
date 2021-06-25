<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class Scholar extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $scholar;
    public $scholar_id_delete;

    public $scholar_id;
    public $firstname;
    public $middlename;
    public $lastname;
    public $gender;
    public $phone;
    public $email;
    public $password;
    public $birthday;

    function rules() {
        return [
            'firstname' => 'required|regex:/^[a-z ,.\'-]+$/i',
            'middlename' => 'required|regex:/^[a-z ,.\'-]+$/i',
            'lastname' => 'required|regex:/^[a-z ,.\'-]+$/i',
            'gender' => 'required|in:male,female',
            'phone' => "required|unique:users".((isset($this->scholar_id))?",phone,$this->scholar_id":'')."|regex:/(09)[0-9]{9}/",
            'birthday' => 'required|before:5 years ago',
            'email' => "required|email|unique:users".((isset($this->scholar_id))?",email,$this->scholar_id":''),
            'password' => 'required|min:9',
        ];
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

        return view('livewire.scholar.scholar', ['scholars' => $scholars]);
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function info($id)
    {
        $this->scholar = User::find($id);

        $this->dispatchBrowserEvent('scholar-info', ['action' => 'show']);
    }

    public function confirm_delete($id)
    {
        $this->scholar_id_delete = $id;

        $confirm = $this->dispatchBrowserEvent('swal:confirm:delete_scholar', [
            'type' => 'warning',  
            'message' => 'Are you sure?', 
            'text' => 'If deleted, you will not be able to recover this account!',
            'function' => "delete"
        ]);
    }

    public function delete()
    {
        $user = User::findorfail($this->scholar_id_delete);
        
        if (!$user->delete()) {
            return;
        }

        $this->scholar = null;

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',  
            'message' => 'Scholar Account Deleted', 
            'text' => 'Scholar account has been successfully deleted'
        ]);

        $this->dispatchBrowserEvent('scholar-info', ['action' => 'hide']);
    }

    public function nullinputs()
    {
        $this->scholar_id   = null;
        $this->firstname    = null;
        $this->middlename   = null;
        $this->lastname     = null;
        $this->gender       = null;
        $this->phone        = null;
        $this->email        = null;
        $this->password     = null;
        $this->birthday     = null;
    }

    public function edit($id)
    {
        $data = User::findorfail($id);

        $this->scholar_id   = $data->id;
        $this->firstname    = $data->firstname;
        $this->middlename   = $data->middlename;
        $this->lastname     = $data->lastname;
        $this->gender       = $data->gender;
        $this->phone        = $data->phone;
        $this->email        = $data->email;
        // $this->password     = $data->password;
        $this->birthday     = $data->birthday;
    }

    public function save()
    {
        if (isset($this->scholar_id)) {
            $this->password = '123123123';
        }

        $data = $this->validate();
        $data['usertype'] = 'scholar';
        if (isset($this->scholar_id)) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $scholar = User::updateOrCreate(
            ['id' => $this->scholar_id],
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
            $this->info($this->scholar_id);
            $this->dispatchBrowserEvent('scholar-form', ['action' => 'hide']);
            return;
        } elseif (!$scholar->wasRecentlyCreated && !$scholar->wasChanged()){
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'info',  
                'message' => 'Nothing has been changed', 
                'text' => ''
            ]);
            $this->edit($this->scholar_id);
            return;
        }
 
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'error',  
            'message' => 'Runtime error!', 
            'text' => ''
        ]);
    }
}
