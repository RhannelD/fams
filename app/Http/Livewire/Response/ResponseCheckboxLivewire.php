<?php

namespace App\Http\Livewire\Response;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarshipRequirementItem;
use App\Models\ScholarshipRequirementItemOption;
use App\Models\ScholarResponse;
use App\Models\ScholarResponseOption;

class ResponseCheckboxLivewire extends Component
{
    public $requirement_item_id;
    public $response_id;
    public $option_id;

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

    public function mount($requirement_item_id, $response_id, $option_id)
    {
        $this->requirement_item_id = $requirement_item_id;
        $this->response_id = $response_id;
        $this->option_id = $option_id;
    }

    public function render()
    {
        return view('livewire.pages.response.response-checkbox-livewire', [
                'option' => $this->get_option(),
                'option_checked' => $this->if_option_checked(), 
                'is_submitted' => $this->get_user_response()->submit_at,
            ]);
    }

    protected function get_requirement()
    {
        $item = ScholarshipRequirementItem::find($this->requirement_item_id);
        return $item? $item->requirement: null;
    }

    protected function get_user_response()
    {
        return ScholarResponse::find($this->response_id);
    }

    protected function get_option()
    {
        return ScholarshipRequirementItemOption::find($this->option_id);
    }

    public function if_option_checked()
    {
        return ScholarResponseOption::where('response_id', $this->response_id)
            ->where('option_id', $this->option_id)
            ->exists();
    }

    protected function cant_update()
    {
        return Auth::guest() || $this->is_admin() || Auth::user()->cannot('respond', $this->get_requirement()) || Auth::user()->cannot('submit', $this->get_user_response());
    }

    public function save()
    {
        if ( $this->cant_update() || is_null($this->get_option()) )
            return;

        if ( $this->if_option_checked() ) {
            return ScholarResponseOption::where('response_id', $this->response_id)
                ->where('option_id', $this->option_id)
                ->delete();
        }

        ScholarResponseOption::firstOrCreate([
            'response_id' =>  $this->response_id,
            'item_id' => $this->requirement_item_id,
            'option_id' => $this->option_id,
        ]);
    }
}
