<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarshipRequirementItem;
use App\Models\ScholarResponseFile;
use Carbon\Carbon;

class ResponseFileUploadLivewire extends Component
{
    use WithFileUploads;

    public $requirement_item;
    public $response_id;
    public $file;
    

    protected $rules = [
        'file' => 'file|max:6000',
    ];

    protected function verifyUser()
    {
        if (!Auth::check() || Auth::user()->usertype != 'scholar') {
            redirect()->route('index');
            return true;
        }
        return false;
    }
 
    public function mount(ScholarshipRequirementItem $id, $response_id)
    {
        if ($this->verifyUser()) return;
        
        $this->requirement_item = $id;
        $this->response_id = $response_id;
    }

    public function render()
    {
        $response_file = ScholarResponseFile::where('response_id', $this->response_id)
            ->where('item_id', $this->requirement_item->id)
            ->first();

        return view('livewire.pages.response.response-file-upload-livewire', ['response_file' => $response_file]);
    }

    public function updated($propertyName)
    {
        if ($this->verifyUser()) return;

        if ( empty($this->get_file_extension()) ) {
            return $this->addError('file', 'Invalid file type!');
        }
   
        $this->validate();

        $this->save();
    }

    public function get_file_extension()
    {
        return pathinfo($this->file->getClientOriginalName(), PATHINFO_EXTENSION);
    }

    public function save()
    {
        $orig_name  = $this->file->getClientOriginalName();

        $user_id    = Auth::id();
        $response_id= $this->response_id;
        $requirement_item_id = $this->requirement_item->id;
        $datetime   = Carbon::now()->format('Y-m-d_h-i-s');
        $extension  = $this->get_file_extension();

        $filename = 'file_'.$user_id.'_'.$response_id.'_'.$requirement_item_id.'_'.$datetime.'.'.$extension;

        $this->file->storeAs('files', $filename);

        $old_file = ScholarResponseFile::where('response_id', $response_id)
            ->where('item_id', $requirement_item_id)
            ->first();
        if ( $old_file ) {
            $old_file->delete_file();
        } 

        $response_file = ScholarResponseFile::updateOrCreate(
            [
                'response_id' => $response_id, 
                'item_id' => $requirement_item_id
            ],
            [
                'file_url' => $filename,
                'file_name' => $orig_name,
            ]
        );
    }

    public function delete($id)
    {
        if ( ScholarResponseFile::where('id', $id)->exists() ) {
            ScholarResponseFile::find($id)->delete();
        }
    }
}
