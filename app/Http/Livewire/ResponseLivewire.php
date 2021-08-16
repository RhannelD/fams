<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ScholarshipScholar;
use App\Models\ScholarshipRequirement;
use App\Models\ScholarshipRequirementItem;
use App\Models\ScholarshipRequirementItemOption;
use App\Models\ScholarshipRequirementCategory;
use App\Models\ScholarResponse;
use App\Models\ScholarResponseFile;
use App\Models\ScholarResponseAnswer;
use App\Models\ScholarResponseOption;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

    protected function verifyUserRequirementAccess($requirement_id, $promote)
    {
        if ( $promote ) {
            return true;
        }

        $access = ScholarshipRequirementCategory::where('requirement_id', $requirement_id)
            ->whereIn('scholarship_requirement_categories.category_id', function($query){
                $query->select('scholarship_scholars.category_id')
                    ->from(with(new ScholarshipScholar)->getTable())
                    ->where('scholarship_scholars.user_id', Auth::id());
            })
            ->exists();

        if (!$access) {
            redirect()->route('index');
        }
        return $access;
    }

    protected function verifyUserResponse()
    {
        if ($this->user_response->user_id != Auth::id()) {
            redirect()->route('index');
            return true;
        }
        return false;
    }

    public function mount(ScholarshipRequirement $id)
    {
        if ($this->verifyUser()) return;
        if (!$this->verifyUserRequirementAccess($id->id, $id->promote)) return;
        
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

    public function submit_response()
    {
        if ($this->verifyUser()) return;
        if (!$this->verifyUserRequirementAccess($this->requirement->id)) return;
        if ($this->verifyUserResponse()) return;

        $file_uploads = ScholarshipRequirement::selectRaw('"file" as item, scholarship_requirement_items.id, scholarship_requirement_items.type')
            ->join(with(new ScholarshipRequirementItem)->getTable(), 'scholarship_requirements.id', '=', 'scholarship_requirement_items.requirement_id')
            ->join(with(new ScholarResponse)->getTable(), 'scholarship_requirements.id', '=', 'scholar_responses.requirement_id')
            ->leftJoin(with(new ScholarResponseFile)->getTable(), function($join) {
                $join->on('scholarship_requirement_items.id', '=', 'scholar_response_files.item_id');
                $join->on('scholarship_requirements.id', '=', 'scholar_response_files.response_id');
            })
            ->whereIn('scholarship_requirement_items.type', ['cor', 'grade', 'file'])
            ->where('scholarship_requirements.id', 1)
            ->where('scholar_responses.id', 1)
            ->whereNull('scholar_response_files.id');

        $asnwer = ScholarshipRequirement::selectRaw('"answer" as item, scholarship_requirement_items.id, scholarship_requirement_items.type')
            ->join(with(new ScholarshipRequirementItem)->getTable(), 'scholarship_requirements.id', '=', 'scholarship_requirement_items.requirement_id')
            ->join(with(new ScholarResponse)->getTable(), 'scholarship_requirements.id', '=', 'scholar_responses.requirement_id')
            ->leftJoin(with(new ScholarResponseAnswer)->getTable(), function($join) {
                $join->on('scholarship_requirement_items.id', '=', 'scholar_response_answers.item_id');
                $join->on('scholarship_requirements.id', '=', 'scholar_response_answers.response_id');
            })
            ->whereIn('scholarship_requirement_items.type', ['question'])
            ->where('scholarship_requirements.id', 1)
            ->where('scholar_responses.id', 1)
            ->whereNull('scholar_response_answers.id');

        $options = ScholarshipRequirement::selectRaw('"option" as item, scholarship_requirement_items.id, scholarship_requirement_items.type')
            ->join(with(new ScholarshipRequirementItem)->getTable(), 'scholarship_requirements.id', '=', 'scholarship_requirement_items.requirement_id')
            ->join(with(new ScholarResponse)->getTable(), 'scholarship_requirements.id', '=', 'scholar_responses.requirement_id')
            ->whereIn('scholarship_requirement_items.type', ['radio', 'check'])
            ->where('scholarship_requirements.id', 1)
            ->where('scholar_responses.id', 1)
            ->whereNotIn('scholar_responses.id', function($query) {
                $query->select('scholar_response_options.response_id')
                    ->from(with(new ScholarshipRequirementItemOption)->getTable())
                    ->join(with(new ScholarResponseOption)->getTable(), 'scholarship_requirement_item_options.id', 'scholar_response_options.option_id')
                    ->whereColumn('scholarship_requirement_item_options.item_id', 'scholarship_requirement_items.id')
                    ->whereColumn('scholar_response_options.response_id', 'scholar_responses.id');
            });

        $unassigned = $options->union($file_uploads)->union($asnwer)->get();


        if (!$unassigned->isEmpty()) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'info',  
                'message' => 'Please fill up all!', 
                'text' => ''
            ]);
            return;
        }

        $this->user_response->submit_at = Carbon::now();
        
        if ( $this->user_response->save() ) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',  
                'message' => 'Response Submitted', 
                'text' => ''
            ]);
        }
    }

    public function unsubmit_response()
    {
        if ($this->verifyUser()) return;
        if (!$this->verifyUserRequirementAccess($this->requirement->id)) return;
        if ($this->verifyUserResponse()) return;

        $this->user_response->submit_at = null;
        $this->user_response->save();
    }
}
