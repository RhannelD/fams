<?php

namespace App\Http\Livewire\Response;

use Livewire\Component;
use App\Models\ScholarResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarResponseAgreement;
use App\Models\ScholarshipRequirementAgreement;

class ResponseAgreementLivewire extends Component
{
    public $agreement_id;
    public $response_id;

    public function hydrate()
    {
        $response = $this->get_user_response();
        if ( Auth::guest() || $this->is_admin() || Auth::user()->cannot('view', $response) || Auth::user()->cannot('respond', $response->requirement) )
            return $this->emitUp('refresh');
    }

    protected function is_admin()
    {
        return Auth::check() && Auth::user()->is_admin();
    }

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

    protected function get_requirement()
    {
        $agreement = $this->get_agreement();
        return $agreement? $agreement->requirement: null;
    }

    protected function get_user_response()
    {
        return ScholarResponse::find($this->response_id);
    }

    protected function get_agreement()
    {
        return ScholarshipRequirementAgreement::with(['response_agreements' => function ($query) {
                $query->where('response_id', $this->response_id);
            }])
            ->where('id', $this->agreement_id)
            ->first();
    }

    protected function cant_update()
    {
        return Auth::guest() || $this->is_admin() || Auth::user()->cannot('respond', $this->get_requirement()) || Auth::user()->cannot('submit', $this->get_user_response());
    }

    public function toggle_check()
    {
        if ( $this->cant_update() || is_null($this->get_agreement()) )
            return;

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
