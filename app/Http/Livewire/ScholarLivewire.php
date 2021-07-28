<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\ScholarshipScholar;
use App\Models\ScholarshipPost;
use App\Models\ScholarshipOfficer;
use App\Models\ScholarshipPostComment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ScholarLivewire extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $user;

    protected $listeners = [
        'info' => 'info',
    ];

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
        if (!Auth::check() || Auth::user()->usertype != 'admin') {
            redirect()->route('dashboard');
            return true;
        }
        return false;
    }
    
    public function getQueryString()
    {
        return [
            'search' => ['except' => ''],
            'user' => ['except' => null]
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

        $user = User::select('id')
            ->where('usertype', 'scholar')
            ->where('id', $this->user)
            ->first();
        if ( !$user ) {
            $this->user = null;
        }

        return view('livewire.pages.scholar.scholar-livewire', ['scholars' => $scholars])
            ->extends('livewire.main.main-livewire');
    }

    public function info($id)
    {
        if ($this->verifyUser()) return;

        if ( !isset($id) ) {
            $this->user = null;
        }

        $this->user = $id;

        $this->dispatchBrowserEvent('scholar-info', ['action' => 'show']);
    }
}
