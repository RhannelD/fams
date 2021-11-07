<?php

namespace App\Http\Livewire\Response;

use Livewire\Component;
use App\Models\ScholarResponse;
use App\Models\ScholarResponseUnit;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarshipRequirementItem;

class ResponseUnitLivewire extends Component
{
    public $requirement_item_id;
    public $response_id;
    public $unit;

    public $units_limit = [
        'min' => 1,
        'max' => 50,
    ];
    
    function rules() {
        return [
            'unit.units' => "required|integer|min:{$this->units_limit['min']}|max:{$this->units_limit['max']}",
        ];
    }

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

    public function mount($requirement_item_id, $response_id)
    {
        $this->requirement_item_id = $requirement_item_id;
        $this->response_id = $response_id;
        $this->unit = new ScholarResponseUnit;

        $this->set_units();
    }

    protected function set_units()
    {
        $unit = ScholarResponseUnit::where('response_id', $this->response_id)
            ->where('item_id', $this->requirement_item_id)
            ->first();

        if ( isset($unit) ) 
            $this->unit->units = $unit->units;
    }

    public function render()
    {
        return view('livewire.pages.response.response-unit-livewire', ['response' => $this->get_user_response()]);
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

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        $this->save();
    }

    protected function cant_update()
    {
        return Auth::guest() || $this->is_admin() || Auth::user()->cannot('respond', $this->get_requirement()) || Auth::user()->cannot('submit', $this->get_user_response());
    }

    protected function save()
    {
        if ( $this->cant_update() ) 
            return;

        $unit = ScholarResponseUnit::firstOrNew([
            'response_id' => $this->response_id, 
            'item_id' => $this->requirement_item_id
        ]);
        
        $unit->units = $this->unit->units;
        $unit->save();
    }
}
