<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ScholarshipRequirementAgreement;
use Illuminate\Support\Facades\Auth;

class ScholarshipRequirementEditAgreementLivewire extends Component
{
    public $agreement_id;
    public $agreement;

    protected $listeners = [
        'save_all' => 'save_all',
    ];

    protected $rules = [
        'agreement' => 'required|string|min:10|max:15000000',
    ];

    protected function verifyUser()
    {
        if (!Auth::check()) {
            redirect()->route('index');
            return true;
        }
        return false;
    }

    public function mount($agreement_id)
    {
        $this->agreement_id = $agreement_id;
    }

    public function render()
    {
        $agreement = $this->get_agreement();
        if ( $agreement ) 
            $this->agreement = $agreement->agreement;

        return view('livewire.pages.scholarship-requirement-edit.scholarship-requirement-edit-agreement-livewire');
    }

    protected function get_agreement()
    {
        return ScholarshipRequirementAgreement::find($this->agreement_id);
    }

    public function updated($propertyName)
    {
        $this->save();
    }

    public function save()
    {
        if ($this->verifyUser()) return;

        $agreement = $this->get_agreement();
        if ( is_null($agreement) )
            return;

        $this->validate();

        $agreement->agreement = $this->agreement;
        $agreement->save();
    }

    public function save_all()
    {
        $this->save();
    }

    public function delete_confirmation()
    {
        if ($this->verifyUser()) return;

        $this->dispatchBrowserEvent('swal:confirm:delete_agreement_confirmation', [
            'type' => 'warning',  
            'message' => 'Are you sure?', 
            'text' => 'If deleted, you will not be able to recover this Terms and Conditions!',
            'function' => "delete_agreement"
        ]);
    }

    public function delete_agreement()
    {
        if ($this->verifyUser()) return;

        $agreement = $this->get_agreement();
        if ( $agreement ) {
            $agreement->delete();
            $this->agreement_id = null;
            $this->emitUp('refresh');
        }
    }
}
