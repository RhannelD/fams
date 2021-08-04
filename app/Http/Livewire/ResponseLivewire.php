<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ScholarResponse;
use App\Models\ScholarshipRequirement;
use App\Models\ScholarshipRequirementItem;
use App\Models\ScholarshipRequirementItemOption;
use Illuminate\Support\Facades\Auth;

class ResponseLivewire extends Component
{
    public $requirement;
    public $user_response;
   
    protected function verifyUser()
    {
        if (!Auth::check() || Auth::user()->usertype != 'scholar') {
            redirect()->route('index');
            return true;
        }
        return false;
    }
 
    public function mount(ScholarshipRequirement $id)
    {
        if ($this->verifyUser()) return;
        
        $this->requirement = $id;

        $this->user_response = ScholarResponse::firstOrCreate([
            'user_id' => Auth::id(),
            'requirement_id' => $this->requirement->id,
        ]);
    }

    public function render()
    {
        $requirement_items =  ScholarshipRequirementItem::where('requirement_id', $this->requirement->id)
            ->orderBy('position')
            ->get();

        foreach ($requirement_items as $key => $requirement_item) {
            if (in_array($requirement_item->type, array('check'))) {
                $options = ScholarshipRequirementItemOption::where('item_id', $requirement_item->id)->get();

                $requirement_items[$key]['options'] = $options;
            }
        }

        return view('livewire.pages.response.response-livewire', [
            'requirement_items' => $requirement_items
        ])->extends('livewire.main.main-livewire');
    }
}
