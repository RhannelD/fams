<?php

namespace App\Http\Livewire\Response;

use Livewire\Component;
use App\Models\ScholarshipRequirementAgreement;
use App\Models\ScholarResponseAgreement;

class ResponseAgreementLivewire extends Component
{
    public $agreement_id;
    public $response_id;

    public function mount($agreement_id, $response_id)
    {
        $this->agreement_id = $agreement_id;
        $this->response_id = $response_id;
    }

    public function render()
    {
        return view('livewire.pages.response.response-agreement-livewire', [
                'agreement' => $this->get_agreement()
            ]);
    }

    protected function get_agreement()
    {
        return ScholarshipRequirementAgreement::with(['response_agreements' => function ($query) {
                $query->where('response_id', $this->response_id);
            }])
            ->where('id', $this->agreement_id)
            ->first();
    }

    public function toggle_check()
    {
        $agreement = ScholarResponseAgreement::where('response_id', $this->response_id)
            ->where('agreement_id', $this->agreement_id)
            ->first();
        if ( isset($agreement) ) {
            $agreement->delete();
            return;
        }

        ScholarResponseAgreement::firstOrCreate([
            'response_id' => $this->response_id, 
            'agreement_id' => $this->agreement_id
        ]);
    }
}
