<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
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

    protected $rules = [
        'firstname' => 'required|regex:/^[a-z ,.\'-]+$/i',
        'middlename' => 'required|regex:/^[a-z ,.\'-]+$/i',
        'lastname' => 'required|regex:/^[a-z ,.\'-]+$/i',
        'gender' => 'required|in:male,female',
        'phone' => 'required|unique:users|regex:/(09)[0-9]{9}/',
        'birthday' => 'required|before:5 years ago',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:9',
    ];


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

    public function save()
    {
        $data = $this->validate();
        $data['usertype'] = 'scholar';

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
            return;
        } elseif (!$scholar->wasRecentlyCreated && $scholar->wasChanged()){
            // updateOrCreate performed an update
        } elseif (!$scholar->wasRecentlyCreated && !$scholar->wasChanged()){
            // updateOrCreate performed nothing, row did not change
        }

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'error',  
            'message' => 'Runtime error!', 
            'text' => ''
        ]);
    }
}
