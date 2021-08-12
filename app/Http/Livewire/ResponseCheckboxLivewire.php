<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarshipRequirementItem;
use App\Models\ScholarshipRequirementItemOption;
use App\Models\ScholarResponse;
use App\Models\ScholarResponseOption;

class ResponseCheckboxLivewire extends Component
{
    public $requirement_item;
    public $response_id;
    public $checkbox;

    protected function verifyUser()
    {
        if (!Auth::check() || Auth::user()->usertype != 'scholar') {
            redirect()->route('index');
            return true;
        }
        return false;
    }

    protected function verifyUserResponse()
    {
        $access = ScholarResponse::where('id', $this->response_id)
            ->where('user_id', Auth::id())
            ->exists();

        if (!$access) {
            redirect()->route('index');
        }
        return !$access;
    }

    public function mount(ScholarshipRequirementItem $requirement_item_id, $response_id, ScholarshipRequirementItemOption $option_id)
    {
        if ($this->verifyUser()) return;
        
        $this->requirement_item = $requirement_item_id;
        $this->response_id = $response_id;
        $this->checkbox = $option_id;
    }

    public function render()
    {
        $option_checked = $this->if_option_checked();

        $is_submitted = ScholarResponse::find($this->response_id)->submit_at;

        return view('livewire.pages.response.response-checkbox-livewire', ['option_checked' => $option_checked, 'is_submitted' => $is_submitted]);
    }

    public function if_option_checked()
    {
        return ScholarResponseOption::where('response_id', $this->response_id)
            ->where('option_id', $this->checkbox->id)
            ->exists();
    }

    public function save()
    {
        if ($this->verifyUser()) return;
        if ($this->verifyUserResponse()) return;

        if ( $this->if_option_checked() ) {
            return ScholarResponseOption::where('response_id', $this->response_id)
                ->where('option_id', $this->checkbox->id)
                ->delete();
        }

        ScholarResponseOption::firstOrCreate([
            'response_id' =>  $this->response_id,
            'option_id' => $this->checkbox->id,
        ]);
    }
}
