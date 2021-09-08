<?php

namespace App\Http\Livewire\Response;

use Livewire\Component;
use App\Models\ScholarshipScholar;
use App\Models\ScholarshipRequirement;
use App\Models\ScholarshipRequirementItem;
use App\Models\ScholarshipRequirementItemOption;
use App\Models\ScholarshipRequirementCategory;
use App\Models\ScholarResponse;
use App\Models\ScholarResponseFile;
use App\Models\ScholarResponseAnswer;
use App\Models\ScholarResponseOption;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ResponseLivewire extends Component
{
    public $requirement_id;
    public $user_response_id = 0;
   
    protected $listeners = [
        'refresh' => '$refresh'
    ];

    protected function verifyUser()
    {
        if (!Auth::check() || Auth::user()->usertype != 'scholar') {
            redirect()->route('index');
            return true;
        }
        return false;
    }

    protected function verifyUserRequirementAccess()
    {
        $requirement = ScholarshipRequirement::find($this->requirement_id);
        if ( is_null($requirement) )
            return false;

        if ( $requirement->promote )
            return true;

        $access = ScholarshipRequirementCategory::where('requirement_id', $this->requirement_id)
            ->whereIn('scholarship_requirement_categories.category_id', function($query){
                $query->select('scholarship_scholars.category_id')
                    ->from(with(new ScholarshipScholar)->getTable())
                    ->where('scholarship_scholars.user_id', Auth::id());
            })
            ->exists();

        if (!$access) {
            redirect()->route('index');
        }
        return $access;
    }

    protected function verifyUserResponse()
    {
        $user_response = ScholarResponse::find($this->user_response_id);
        if ( is_null($user_response) || $user_response->user_id != Auth::id()) {
            redirect()->route('index');
            return;
        }
        return false;
    }

    public function mount($requirement_id)
    {
        if ($this->verifyUser()) return;
        
        $this->requirement_id = $requirement_id;

        if (!$this->verifyUserRequirementAccess()) return;
    }

    public function render()
    {
        $requirement = ScholarshipRequirement::find($this->requirement_id);

        $user_response = null;
        if ( isset($requirement) ) {
            $user_response = ScholarResponse::firstOrCreate([
                    'user_id' => Auth::id(),
                    'requirement_id' => $this->requirement_id,
                ]);

            $this->user_response_id = $user_response->id;
        }

        return view('livewire.pages.response.response-livewire', [
                'requirement' => $requirement,
                'user_response' => $user_response
            ])
            ->extends('livewire.main.main-livewire');
    }

    public function submit_response()
    {
        if ($this->verifyUser()) return;
        if (!$this->verifyUserRequirementAccess()) return;
        if ($this->verifyUserResponse()) return;

        $user_response = ScholarResponse::find($this->user_response_id);
        if ( is_null($user_response) )
            return;

        $response_id = $this->user_response_id;

        $missing = ScholarshipRequirement::where('id', $this->requirement_id)
            ->where(function ($query) use ($response_id) {
                $query->whereHas('items', function ($query) use ($response_id) {
                        $query->whereIn('type', ['cor', 'grade', 'file'])
                            ->whereDoesntHave('response_files', function ($query) use ($response_id) {
                                $query->where('response_id', $response_id);
                            });
                    })
                    ->orWhereHas('items', function ($query) use ($response_id) {
                        $query->where('type', 'question')
                            ->whereDoesntHave('response_answer', function ($query) use ($response_id) {
                                $query->where('response_id', $response_id);
                            });
                    })
                    ->orWhereHas('items', function ($query) use ($response_id) {
                        $query->whereIn('type', ['radio', 'check'])
                            ->whereDoesntHave('options', function ($query) use ($response_id) {
                                $query->whereHas('responses', function ($query) use ($response_id) {
                                    $query->where('response_id', $response_id);
                                });
                            });
                    })
                    ->orWhereHas('agreements', function ($query) use ($response_id) {
                        $query->whereDoesntHave('response_agreements', function ($query) use ($response_id) {
                                $query->where('response_id', $response_id);
                            });
                    });
            })
            ->get();

        if ( $missing->count() > 0 ) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'info',  
                'message' => 'Please fill up all items!', 
                'text' => ''
            ]);
            return;
        }

        $user_response->submit_at = Carbon::now();
        
        if ( $user_response->save() ) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',  
                'message' => 'Response Submitted', 
                'text' => ''
            ]);
        }
    }

    public function unsubmit_response()
    {
        if ($this->verifyUser()) return;
        if (!$this->verifyUserRequirementAccess()) return;
        if ($this->verifyUserResponse()) return;

        $user_response = ScholarResponse::find($this->user_response_id);
        if ( is_null($user_response) )
            return;

        $user_response->submit_at = null;
        $user_response->save();
    }
}
