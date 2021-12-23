<?php

namespace App\Http\Livewire\BackupRestore;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class BackupFileUploadLivewire extends Component
{
    use WithFileUploads;
    
    public $file;

    protected $rules = [
        'file' => 'file|max:8192',
    ];

    public function hydrate()
    {
        if ( !Gate::allows('backup-restore-upload') ) {
            return redirect()->route('back_restore');
        }
    }

    public function render()
    {
        return view('livewire.pages.backup-restore.backup-file-upload-livewire', [
                'filename' => $this->get_filename(),
            ]);
    }

    protected function get_filename()
    {
        return isset($this->file)? $this->file->getClientOriginalName(): null;
    }
    
    public function get_file_extension()
    {
        return pathinfo($this->file->getClientOriginalName(), PATHINFO_EXTENSION);
    }
    
    public function get_file_name_only()
    {
        return pathinfo($this->file->getClientOriginalName(), PATHINFO_FILENAME);
    }

    public function updated($propertyName)
    {
        $this->resetErrorBag();
        $this->resetValidation();

        if ( empty($this->get_file_extension()) || $this->get_file_extension() != "sql" ) {
            return $this->addError('file', 'Invalid file type!');
        }
   
        $this->validate();
    }

    public function upload()
    {
        if ( !Gate::allows('backup-restore-upload') ) {
            return;
        }
        
        $this->validate();

        if ( empty($this->get_file_extension()) || $this->get_file_extension() != "sql" ) {
            return $this->addError('file', 'Invalid file type!');
        }
        
        $orig_name  = $this->file->getClientOriginalName();

        $temp_name   = '';
        $temp_number = 1;
        if ( $this->file_exists($orig_name) ) {
            do {
                $temp_number++;
                $file_name_only = $this->get_file_name_only();
                $file_extension = $this->get_file_extension();

                $temp_name   = "{$file_name_only}_({$temp_number}).{$file_extension}";
                if ( !$this->file_exists($temp_name) ) {
                    break;
                }
            } while (true);
            $orig_name = $temp_name;
        }

        $this->file->storeAs('backups', $orig_name);
        $this->reset('file');
        $this->emitUp('uploaded', $orig_name);
        $this->dispatchBrowserEvent('upload-form', ['action' => 'hide']);
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',  
            'message' => 'File Uploaded Successfully!', 
            'text' => ''
        ]);
    }
    
    protected function file_exists($filename)
    {
        return Storage::disk('backups')->exists($filename);
    }
}
