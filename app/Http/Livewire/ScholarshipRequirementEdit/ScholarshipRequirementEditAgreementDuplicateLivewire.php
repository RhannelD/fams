<?php

namespace App\Http\Livewire\ScholarshipRequirementEdit;

use Livewire\Component;
use App\Models\ScholarshipRequirement;
use App\Models\ScholarshipRequirementAgreement;
use Illuminate\Support\Facades\Auth;

class ScholarshipRequirementEditAgreementDuplicateLivewire extends Component
{
    public $agreement_id;
    public $duplicate_agreement_requirement_id;
    
    public $search;
    public $show_row = 10;

    protected $listeners = [
        'refresh' => '$refresh',
    ];
    
    public function mount($agreement_id)
    {
        $this->agreement_id = $agreement_id;
    }

    public function render()
    {
        return view('livewire.pages.scholarship-requirement-edit.scholarship-requirement-edit-agreement-duplicate-livewire', [
                'requirements' => $this->get_scholarship_requirements(),
                'duplicate_agreement_requirement' => $this->get_duplicate_agreement_requirement()
            ]);
    }
    
    protected function get_scholarship_requirements()
    {
        $agreement = $this->get_agreement();
        if ( is_null($agreement) )
            return;

        $search = $this->search;
        $agreement_id = $this->agreement_id;
        return ScholarshipRequirement::where('scholarship_id', $agreement->requirement->scholarship_id)
            ->has('agreements')
            ->whereHas('agreements', function ($query) use ($agreement_id) {
                $query->where('id', '!=', $agreement_id);
            })
            ->where('scholarship_requirements.requirement', 'like', "%$search%")
            ->take($this->show_row)
            ->get();
    }

    protected function get_agreement()
    {
        return ScholarshipRequirementAgreement::find($this->agreement_id);
    }

    protected function get_duplicate_agreement_requirement()
    {
        return ScholarshipRequirement::find($this->duplicate_agreement_requirement_id);
    }

    public function view_requirement($requirement_id)
    {
        if ( !ScholarshipRequirement::where('id', $requirement_id)->has('agreements')->exists() )
            return;
        
        $this->duplicate_agreement_requirement_id = $requirement_id;
        $this->dispatchBrowserEvent('change:tab.view');  
    }
    
    public function duplication_confirm()
    {
        if ( is_null($this->get_duplicate_agreement_requirement()) )
            return;
        
        $this->dispatchBrowserEvent('swal:confirm:duplicate_agreement', [
            'type' => 'warning',  
            'message' => 'Are you sure?', 
            'text' => 'Duplicating selected Agreement.',
            'function' => "agreement_duplication"
        ]);
    }

    public function agreement_duplication()
    {
        $agreement = $this->get_agreement();
        $duplicate_agreement_requirement = $this->get_duplicate_agreement_requirement();
        if ( is_null($agreement) || is_null($duplicate_agreement_requirement) )
            return;

        $agreement->agreement = $duplicate_agreement_requirement->agreements->first()->agreement;
        $agreement->save();
        $this->emitUp('refresh');
        $this->dispatchBrowserEvent('agreement-duplicate-form', ['action' => 'hide']);
    }
}
