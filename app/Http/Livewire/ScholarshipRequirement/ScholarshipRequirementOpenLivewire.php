<?php

namespace App\Http\Livewire\ScholarshipRequirement;

use Livewire\Component;
use App\Models\ScholarResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarshipRequirement;
use App\Models\ScholarshipRequirementItem;
use App\Models\ScholarshipPostLinkRequirement;
use App\Models\ScholarshipRequirementCategory;
use App\Models\ScholarshipRequirementItemOption;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ScholarshipRequirementOpenLivewire extends Component
{
    use AuthorizesRequests;
    
    public $requirement_id;

    protected $listeners = [
        'refresh' => '$refresh',
    ];

    public function hydrate()
    {
        if ( Auth::guest() || Auth::user()->cannot('update', $this->get_scholarship_requirement()) ) {
            return redirect()->route('scholarship.requirement.open', [$this->requirement_id]);
        }
    }

    public function mount($requirement_id)
    {
        $this->requirement_id = $requirement_id;
        $this->authorize('update', $this->get_scholarship_requirement());
    }

    public function render()
    {
        return view('livewire.pages.scholarship-requirement-open.scholarship-requirement-open-livewire', [
                'requirement' => $this->get_scholarship_requirement()
            ])
            ->extends('livewire.main.scholarship');
    }

    protected function get_scholarship_requirement()
    {
        return ScholarshipRequirement::find($this->requirement_id);
    }

    public function delete_confirmation()
    {
        if ( Auth::check() && Auth::user()->can('delete', $this->get_scholarship_requirement()) ) {
            if ( $this->if_requirement_cant_be_deleted() ) 
                return;

            $this->dispatchBrowserEvent('swal:confirm:delete_requirement', [
                'type' => 'warning',  
                'message' => 'Are you sure?', 
                'text' => 'If deleted, you will not be able to recover this requirement!',
                'function' => "delete_requirement"
            ]);
        }
    }

    protected function if_requirement_cant_be_deleted()
    {
        $requirement = $this->get_scholarship_requirement();
        if ( is_null($requirement) )
            return true;
        
        if ( $requirement->get_submitted_responses_count() ) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'info',  
                'message' => 'Requirement can\'t be deleted!', 
                'text' => 'The requirement has scholars\' responses already.'
            ]);
            return true;
        }
        return false;
    }

    public function delete_requirement()
    {
        $requirement = $this->get_scholarship_requirement();
        if ( Auth::guest() || Auth::user()->cannot('delete', $requirement) ) 
            return;
        
        if ( $this->if_requirement_cant_be_deleted() ) 
            return;

        $scholarship_id = $requirement->scholarship_id;
        if ($requirement->delete()) {
            session()->flash('deleted', 'Requirement successfully deleted.');
            redirect()->route('scholarship.requirement', [$scholarship_id]);
        }
    }

    public function edit_confirm()
    {
        $requirement = $this->get_scholarship_requirement();
        if ( Auth::guest() || Auth::user()->cannot('update', $requirement) )
            return;

        if ( $requirement->get_submitted_responses_count() ) {
            $this->dispatchBrowserEvent('swal:confirm:edit_requirement', [
                'type' => 'warning',  
                'message' => 'Continue to edit?', 
                'text' => 'Editing this requirement might affect the submitted responses',
                'function' => "edit"
            ]);
        }
    }

    public function edit()
    {
        if ( Auth::guest() || Auth::user()->cannot('update', $this->get_scholarship_requirement()) )
            return;

        return redirect()->route('scholarship.requirement.edit', [$this->requirement_id]);
    }
}
