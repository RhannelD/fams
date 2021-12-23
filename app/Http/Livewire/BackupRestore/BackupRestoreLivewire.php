<?php

namespace App\Http\Livewire\BackupRestore;

use Carbon\Carbon;
use Livewire\Component;
use Ifsnop\Mysqldump\Mysqldump;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class BackupRestoreLivewire extends Component
{
    public $file_created = '';

    public $show_row = 10;

    protected $listeners = [
        'uploaded' => 'uploaded',
    ];

    public function getQueryString()
    {
        return [
            'show_row' => ['except' => 10],
        ];
    }

    public function hydrate()
    {
        if ( !Gate::allows('backup-restore-view') ) {
            return redirect()->route('back_restore');
        }
    }

    public function mount()
    {
        if ( !Gate::allows('backup-restore-view') ) abort('403', 'THIS ACTION IS UNAUTHORIZED.');
    }

    public function uploaded($file_created)
    {
        $this->file_created = $file_created;
    }

    public function render()
    {
        return view('livewire.pages.backup-restore.backup-restore-livewire', [
                'files' => $this->get_files(),
            ])
            ->extends('livewire.main.main-livewire');
    }

    protected function get_files()
    {
        $array = [];
        $files = Storage::disk('backups')->files();

        foreach ($files as $key => $file) {
            $temp_arr = [];
            $temp_arr['filename'] = $file;
            $temp_arr['lastModified'] = Carbon::createFromTimestamp(Storage::disk('backups')->lastModified($file))->toDateTimeString(); ;
            $array[$key] = $temp_arr;
        }

        return collect($array)->sortByDesc('lastModified')->values()->all();
    }

    public function load_more()
    {
        $this->show_row += 10;
    }

    public function create_backup()
    {
        if ( !Gate::allows('backup-restore-create') ) {
            return;
        }

        $filename = env('APP_NAME').'_'.Carbon::now()->format('Y_m_d_H_i_s').'.sql';

        try {
            $dump = new Mysqldump('mysql:host='.env('DB_HOST').';dbname='.env('DB_DATABASE'), env('DB_USERNAME'), env('DB_PASSWORD'));
            $dump->start(Storage::path('backups').'/'.$filename);

            $this->file_created = $filename;

            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',  
                'message' => 'Backup Successfully!', 
                'text' => ''
            ]);
        } catch (\Exception $e) {
        }
    }

    public function download($filename)
    {
        if ( Gate::allows('backup-restore-download') ) {
            return Storage::disk('backups')->download($filename);
        }
    }

    protected function file_exists($filename)
    {
        return Storage::disk('backups')->exists($filename);
    }

    public function delete_confirm($filename)
    {
        if ( Auth::guest() || !Gate::allows('backup-restore-delete') || !$this->file_exists($filename) ) 
            return;

        $this->dispatchBrowserEvent('swal:confirm:delete_backup', [
            'type' => 'warning',  
            'message' => 'Are you sure?', 
            'text' => 'If deleted, you will not be able to recover this backup file!',
            'backup_filename' => $filename,
        ]);
    }

    public function delete($filename)
    {
        if ( Gate::allows('backup-restore-delete') || $this->file_exists($filename) ) {
            Storage::disk('backups')->delete($filename);

            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',  
                'message' => 'Backup file Deleted Successfully!', 
                'text' => ''
            ]);
        }
    }

    protected function valid_file_type($filename)
    {
        return pathinfo(storage_path(Storage::disk('backups')->path($filename)), PATHINFO_EXTENSION) == 'sql';
    }

    public function restore_confirm($filename)
    {
        if ( Auth::guest() || !Gate::allows('backup-restore-restore') || !$this->file_exists($filename) ) {
            return;
        } elseif ( !$this->valid_file_type($filename) ) {
            return $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',  
                'message' => 'Invalid file type!', 
                'text' => ''
            ]);
        }

        $this->dispatchBrowserEvent('swal:confirm:restore_database', [
            'type' => 'warning',  
            'message' => 'Are you sure?', 
            'text' => 'If restored, you will not be able to recover the current records!',
            'backup_filename' => $filename,
        ]);
    }

    public function restore($filename)
    {
        if ( !$this->valid_file_type($filename) ) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',  
                'message' => 'Invalid file type!', 
                'text' => ''
            ]);
        } elseif ( Gate::allows('backup-restore-restore') || $this->file_exists($filename) ) {
            try {
                $this->drop_all_tables();
                $this->import_file($filename);

                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'success',  
                    'message' => 'Restore Successfully!', 
                    'text' => ''
                ]);
            } catch (\Exception $e) {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',  
                    'message' => 'Runtime error!', 
                    'text' => ''
                ]);
            }
        }
    }

    protected function drop_all_tables()
    {
        $tables = DB::select('SHOW TABLES');

        $tables_array = [];
        foreach ($tables as $value) {
            $tables_array[] = (array) $value;
        }

        Schema::disableForeignKeyConstraints();
        foreach ($tables_array as $table) {
            foreach ($table as $value) {
                Schema::drop( $value );
            }
        }
        Schema::enableForeignKeyConstraints();
    }

    protected function import_file($filename)
    {
        $filename = Storage::disk('backups')->path($filename);
        $templine = '';
        $lines = file($filename);
        foreach ($lines as $line)
        {
            if (substr($line, 0, 2) == '--' || $line == '')
                continue;

            $templine .= $line;
            if (substr(trim($line), -1, 1) == ';')
            {
                DB::unprepared($templine);
                $templine = '';
            }
        }
    }
}
