<?php

namespace App\Http\Livewire\Response;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarshipRequirementItem;
use App\Models\ScholarResponse;
use App\Models\ScholarResponseFile;
use Carbon\Carbon;

class ResponseFileUploadLivewire extends Component
{
    use WithFileUploads;

    public $requirement_item_id;
    public $response_id;
    public $file;
    

    protected $rules = [
        'file' => 'file|max:6000',
    ];

    public function hydrate()
    {
        $response = $this->get_user_response();
        if ( Auth::guest() || $this->is_admin() || Auth::user()->cannot('view', $response) || Auth::user()->cannot('respond', $response->requirement) || is_null($this->get_requirement_item()) )
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
    }

    public function render()
    {
        return view('livewire.pages.response.response-file-upload-livewire', [
                'requirement_item' => $this->get_requirement_item(),
                'response' => $this->get_user_response(),
                'response_file' => $this->get_response_file(),
            ]);
    }

    protected function get_requirement()
    {
        $item = $this->get_requirement_item();
        return $item? $item->requirement: null;
    }

    protected function get_requirement_item()
    {
        return ScholarshipRequirementItem::find($this->requirement_item_id);
    }

    protected function get_user_response()
    {
        return ScholarResponse::find($this->response_id);
    }

    protected function get_response_file()
    {
        return ScholarResponseFile::where('response_id', $this->response_id)->where('item_id', $this->requirement_item_id)->first();
    }

    public function updated($propertyName)
    {
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

    protected function cant_update()
    {
        return Auth::guest() || $this->is_admin() || Auth::user()->cannot('respond', $this->get_requirement()) || Auth::user()->cannot('submit', $this->get_user_response());
    }

    public function save()
    {
        if ( $this->cant_update() ) 
            return;

        $orig_name  = $this->file->getClientOriginalName();

        $user_id        = Auth::id();
        $response_id    = $this->response_id;
        $requirement_item_id = $this->requirement_item_id;
        $datetime       = Carbon::now()->format('Y-m-d_h-i-s');
        $extension      = $this->get_file_extension();

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
        if ( $this->cant_update() ) 
            return;
        
        if ( ScholarResponseFile::where('id', $id)->exists() ) {
            ScholarResponseFile::find($id)->delete();
        }
    }
}
