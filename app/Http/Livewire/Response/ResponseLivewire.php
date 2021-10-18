<?php

namespace App\Http\Livewire\Response;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\ScholarResponse;
use App\Models\ScholarshipScholar;
use Illuminate\Support\Facades\DB;
use App\Models\ScholarResponseFile;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarResponseAnswer;
use App\Models\ScholarResponseOption;
use App\Models\ScholarshipRequirement;
use App\Models\ScholarshipRequirementItem;
use App\Models\ScholarshipRequirementCategory;
use App\Models\ScholarshipRequirementItemOption;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ResponseLivewire extends Component
{
    use AuthorizesRequests;
    
    public $requirement_id;
    public $response_id;
   
    protected $listeners = [
        'refresh' => '$refresh'
    ];

    public function hydrate()
    {
        if ( $this->is_admin() ) 
            return redirect()->route('scholarship.requirement.open', [$this->requirement_id]);

        if ( Auth::guest() || Auth::user()->cannot('respond', $this->get_requirement()) || Auth::user()->cannot('view', $this->get_user_response()) )
            return redirect()->route('requirement.response', [$this->requirement_id]);
    }

    protected function is_admin()
    {
        return Auth::check() && Auth::user()->is_admin();
    }

    public function mount($requirement_id)
    {
        $this->requirement_id = $requirement_id;
        $this->authorize('respond', $this->get_requirement());

        if ( $this->is_admin() ) 
            return redirect()->route('scholarship.requirement.open', [$this->requirement_id]);
        
        $response = ScholarResponse::firstOrCreate([
                'user_id' => Auth::id(),
                'requirement_id' => $this->requirement_id,
            ]);
        $this->response_id = $response->id;
    }

    public function render()
    {
        return view('livewire.pages.response.response-livewire', [
                'requirement' => $this->get_requirement(),
                'user_response' => $this->get_user_response(),
            ])
            ->extends('livewire.main.main-livewire');
    }

    protected function get_requirement()
    {
        return ScholarshipRequirement::find($this->requirement_id);
    }

    protected function get_user_response()
    {
        return ScholarResponse::find($this->response_id);
    }

    public function submit_response()
    {
        $user_response = $this->get_user_response();
        if ( Auth::guest() || $this->is_admin() || Auth::user()->cannot('respond', $this->get_requirement()) || Auth::user()->cannot('submit', $user_response) )
            return;

        $response_id = $this->response_id;

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
        $user_response = $this->get_user_response();
        if ( Auth::guest() || $this->is_admin() || Auth::user()->cannot('respond', $this->get_requirement()) || Auth::user()->cannot('unsubmit', $user_response) )
            return;

        if ( $user_response->requirement->enable == true || !$user_response->submmited_on_time() ) 
            return $this->dispatchBrowserEvent('swal:confirm:unsubmit_response', [
                'type' => 'warning',  
                'message' => 'Are you sure?', 
                'text' => 'Unsubmiting the response!',
                'function' => "unsubmiting_response"
            ]);
    }

    public function unsubmiting_response()
    {
        $user_response = $this->get_user_response();
        if ( Auth::guest() || $this->is_admin() || Auth::user()->cannot('respond', $this->get_requirement()) || Auth::user()->cannot('unsubmit', $user_response) )
            return;

        $user_response->submit_at = null;
        $user_response->save();
    }
}
