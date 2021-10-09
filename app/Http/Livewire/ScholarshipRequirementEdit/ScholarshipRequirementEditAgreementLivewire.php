<?php

namespace App\Http\Livewire\ScholarshipRequirementEdit;

use Livewire\Component;
use App\Models\ScholarshipRequirementAgreement;
use Illuminate\Support\Facades\Auth;

class ScholarshipRequirementEditAgreementLivewire extends Component
{
    public $agreement_id;
    public $agreement;

    protected $listeners = [
        'save_all' => 'save_all',
        'refresh' => 'refreshing',
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

    public function hydrate()
    {
        if ( Auth::guest() || Auth::user()->cannot('update', $this->get_agreement()) ) {
            $this->emitUp('refresh');
        }
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

    public function refreshing()
    {
        $agreement = $this->get_agreement();
        if ( Auth::check() && Auth::user()->can('update', $agreement) ) {
            $this->agreement = $agreement->agreement;

            $this->dispatchBrowserEvent('refreshing-agreement', ['agreement' => $this->agreement]);
        }
    }

    public function save()
    {
        $agreement = $this->get_agreement();
        if ( Auth::check() && Auth::user()->can('update', $agreement) ) {
            $this->validate();

            $agreement->agreement = $this->agreement;
            $agreement->save();
        }
    }

    public function save_all()
    {
        $this->save();
    }

    public function delete_confirmation()
    {
        if ( Auth::check() && Auth::user()->can('delete', $this->get_agreement()) ) {
            $this->dispatchBrowserEvent('swal:confirm:delete_agreement_confirmation', [
                'type' => 'warning',  
                'message' => 'Are you sure?', 
                'text' => 'If deleted, you will not be able to recover this Terms and Conditions!',
                'function' => "delete_agreement"
            ]);
        }
    }

    public function delete_agreement()
    {
        $agreement = $this->get_agreement();
        if ( Auth::check() && Auth::user()->can('delete', $agreement) ) {
            $agreement->delete();
            $this->agreement_id = null;
            $this->emitUp('refresh');
        }
    }
}
