<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\ScholarshipRequirementItem;
use Illuminate\Support\Facades\Auth;

class ResponseFileUploadLivewire extends Component
{
    use WithFileUploads;

    public $requirement_item;
    public $photo;
    

    protected $rules = [
        'photo' => 'file|max:10240',
    ];

    protected function verifyUser()
    {
        if (!Auth::check() || Auth::user()->usertype != 'scholar') {
            redirect()->route('index');
            return true;
        }
        return false;
    }
 
    public function mount(ScholarshipRequirementItem $id)
    {
        if ($this->verifyUser()) return;
        
        $this->requirement_item = $id;
    }

    public function render()
    {
        return view('livewire.pages.response.response-file-upload-livewire');
    }

    public function updated($propertyName)
    {
        if ($this->verifyUser()) return;
        
        $this->validate();

        $this->save();
    }
    
    public function save()
    {
        // $this->photo->store('photos');
    }
}
