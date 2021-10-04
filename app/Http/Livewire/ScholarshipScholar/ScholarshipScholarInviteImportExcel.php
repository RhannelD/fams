<?php

namespace App\Http\Livewire\ScholarshipScholar;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use App\Models\ScholarshipCategory;
use App\Imports\ScholarInviteImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Input\Input;

class ScholarshipScholarInviteImportExcel extends Component
{
    use WithFileUploads;
    
    public $scholarship_id;
    public $excel;
    
    public $dataset;
    public $dataset_invalid;

    protected $rules = [
        'excel' => 'file|max:6000',
    ];

    public function mount($scholarship_id)
    {
        $this->scholarship_id = $scholarship_id;
    }

    public function render()
    {
        return view('livewire.pages.scholarship-scholar.scholarship-scholar-invite-import-excel')
            ->extends('livewire.main.main-livewire');
    }

    public function updated($propertyName)
    {
        $this->validate();

        if ( !$this->valid_file_type() ) {
            return $this->addError('excel', 'Invalid file type!');
        }

        $this->save();
    }

    protected function valid_file_type()
    {
        return in_array($this->get_file_extension(), ['xlsx']);
    }

    protected function get_file_extension()
    {
        return pathinfo($this->excel->getClientOriginalName(), PATHINFO_EXTENSION);
    }

    protected function save()
    {
        $import = new ScholarInviteImport;
        Excel::import($import, $this->excel);
        $modified_array = $import->getArray();
        
        if ( !isset($modified_array) || !is_array($modified_array) ) {
            $this->dataset = null;
            $this->dataset_invalid = null;
            return;
        }

        $dataset = [];
        $dataset_invalid = [];
        foreach ($modified_array as $row) {
            $validated_data = $this->get_validated_data($row);

            if ( !$validated_data->fails() ) {
                $dataset[] = $row;
            } else {
                $invalid_row = $row;
                $invalid_row['error'] = $validated_data->errors()->all();
                $dataset_invalid[] = $invalid_row;
            }
        }

        $this->dataset = $dataset;
        $this->dataset_invalid = $dataset_invalid;
    }

    protected function get_validated_data($row)
    {
        $scholarship_id =  $this->scholarship_id;
        return Validator::make($row, [
            'email'    => 'required|email',
            'category' => [
                    'required',
                    Rule::exists('scholarship_categories')->where(function ($query) use ($scholarship_id) {
                        return $query->where('scholarship_id', $scholarship_id);
                    }),
                ],
        ]);
    }
}
