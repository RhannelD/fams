<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\ScholarshipCategory;
use App\Models\ScholarshipScholarInvite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ScholarshipScholarInviteLivewire extends Component
{
    public $scholarship_id;
    public $name_email;
    public $category_id = 0;

    protected $rules = [
        'name_email' => 'required|email|unique:users,email',
        'category_id' => 'required|exists:scholarship_categories,id',
    ];
    
    protected function verifyUser()
    {
        if (!Auth::check()) {
            redirect()->route('index');
            return true;
        }
        return false;
    }
    
    public function mount($scholarship_id)
    {
        if ($this->verifyUser()) return;
        
        $this->scholarship_id = $scholarship_id;

        $categories = $this->get_categories();
        if ( isset($categories[0]->id) ) {
            $this->category_id = $categories[0]->id;
        }
    }

    public function render()
    {
        return view('livewire.pages.scholarship-scholar.scholarship-scholar-invite-livewire', [
                'categories' => $this->get_categories(),
                'invites' => $this->get_scholarship_invites(),
                'search_officers' => $this->get_search_name_email(),
            ]);
    }

    protected function get_categories()
    {
        return ScholarshipCategory::where('scholarship_id', $this->scholarship_id)->get();
    }

    protected function get_scholarship_invites()
    {
        return ScholarshipScholarInvite::whereScholarship($this->scholarship_id)->get();
    }

    protected function get_search_name_email()
    {
        if ( empty($this->name_email) )
            return null;

        $scholarship_id = $this->scholarship_id;
        return User::whereScholar()
            ->whereNameOrEmail($this->name_email)
            ->whereNotScholarOf($this->scholarship_id)
            ->whereDoesntHave('scholars_invites', function ($query) use ($scholarship_id) {
                $query->whereHas('category', function($query) use ($scholarship_id) {
                    $query->where('scholarship_id', $scholarship_id);
                });
            })
            ->limit(5)
            ->get();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        $scholarship_id = $this->scholarship_id;
        $invite = ScholarshipScholarInvite::where('email', $this->name_email)
            ->whereHas('category', function($query) use ($scholarship_id) {
                $query->where('scholarship_id', $scholarship_id);
            })
            ->exists();
        if ( $invite ) {
            $this->addError('name_email', '.');
        }
    }

    public function invite_email($email)
    {
        $this->validateOnly('category_id');

        $invite = ScholarshipScholarInvite::updateOrCreate([
                'email' => $email  
            ], [
                'category_id' => $this->category_id,
            ]);
    }

    public function cancel_invite($invite_id)
    {
        ScholarshipScholarInvite::where('id', $invite_id)->delete();
    }
}
